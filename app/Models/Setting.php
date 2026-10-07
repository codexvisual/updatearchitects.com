<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Setting extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['key', 'value', 'group', 'description', 'public', 'locale'];

    protected $casts = ['value' => 'array', 'public' => 'boolean'];

    /** @var list<string> Locales whose resolved map is currently bound to the container. */
    private static array $resolvedLocales = [];

    public function translations()
    {
        return $this->hasMany(SettingTranslation::class);
    }

    public function translation(string $locale): HasOne
    {
        return $this->hasOne(SettingTranslation::class)->where('locale', $locale);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['key', 'value', 'group'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public static function getValue(string $key, string $locale = 'en', $default = null)
    {
        return static::resolvedMap($locale)[$key] ?? $default;
    }

    public static function getPublicSettings(string $locale = 'en'): array
    {
        return static::query()
            ->where('public', true)
            ->pluck('key')
            ->mapWithKeys(fn (string $key) => [$key => static::getValue($key, $locale)])
            ->all();
    }

    /**
     * Every setting for a locale, keyed by setting key.
     *
     * Resolved with two queries and bound to the container for the lifetime of
     * the current request, so views may call getValue() in loops without
     * issuing a query per call.
     *
     * @return array<string, mixed>
     */
    private static function resolvedMap(string $locale): array
    {
        $containerKey = "settings.map.{$locale}";

        if (app()->bound($containerKey)) {
            return app($containerKey);
        }

        $translations = SettingTranslation::query()
            ->get(['setting_id', 'locale', 'value'])
            ->groupBy('setting_id')
            ->map(fn ($rows) => $rows->mapWithKeys(fn ($row) => [$row->locale => $row->value]));

        $map = [];

        foreach (static::query()->get() as $setting) {
            $map[$setting->key] = $translations->get($setting->id)[$locale] ?? $setting->value;
        }

        app()->instance($containerKey, $map);

        if (! in_array($locale, static::$resolvedLocales, true)) {
            static::$resolvedLocales[] = $locale;
        }

        return $map;
    }

    /**
     * Drop the memoised maps so the next getValue() re-reads the database.
     */
    public static function flushResolvedMaps(): void
    {
        foreach (static::$resolvedLocales as $locale) {
            app()->forgetInstance("settings.map.{$locale}");
        }

        static::$resolvedLocales = [];
    }
}
