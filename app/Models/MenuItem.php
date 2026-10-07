<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class MenuItem extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['menu_id', 'parent_id', 'title', 'url', 'target', 'type', 'route_params', 'sort_order', 'visibility', 'locale'];

    protected $casts = ['route_params' => 'array', 'sort_order' => 'integer', 'visibility' => 'boolean'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort_order');
    }

    public function translations()
    {
        return $this->hasMany(MenuItemTranslation::class);
    }

    public function translation(string $locale): HasOne
    {
        return $this->hasOne(MenuItemTranslation::class)->where('locale', $locale);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['menu_id', 'parent_id', 'title'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function scopeVisible($query)
    {
        return $query->where('visibility', true);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
}
