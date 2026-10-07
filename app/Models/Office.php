<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Office extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['slug', 'name', 'address', 'phone', 'email', 'map_embed', 'sort_order', 'visibility', 'locale'];

    protected $casts = ['sort_order' => 'integer', 'visibility' => 'boolean'];

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class)->orderBy('sort_order');
    }

    public function translations()
    {
        return $this->hasMany(OfficeTranslation::class);
    }

    public function translation(string $locale): HasOne
    {
        return $this->hasOne(OfficeTranslation::class)->where('locale', $locale);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['slug', 'name', 'address'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function scopeVisible($query)
    {
        return $query->where('visibility', true);
    }

    /**
     * The mailbox new website enquiries are sent to.
     *
     * Read from the CMS office record so no address is hardcoded anywhere;
     * returns null when no office has an address configured yet.
     */
    public static function enquiryRecipient(): ?string
    {
        $email = static::visible()
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->value('email');

        return $email !== null && $email !== '' ? $email : null;
    }
}
