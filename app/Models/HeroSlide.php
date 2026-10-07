<?php

namespace App\Models;

use App\Models\Concerns\HasResponsiveMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class HeroSlide extends Model implements HasMedia
{
    use HasFactory, HasResponsiveMedia, InteractsWithMedia, LogsActivity, SoftDeletes {
        // Spatie ships an empty stub of this method; ours wins and generates
        // the widths listed in config('media-library.responsive_widths').
        HasResponsiveMedia::registerMediaConversions insteadof InteractsWithMedia;
    }

    protected $fillable = [
        'eyebrow', 'title', 'subtitle', 'cta_label', 'cta_url',
        'secondary_cta_label', 'secondary_cta_url', 'image_id', 'mobile_image_id',
        'sort_order', 'visibility', 'locale',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'visibility' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'visibility', 'locale'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->singleFile();

        $this->addMediaCollection('featured_mobile')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->singleFile();
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function mobileImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'mobile_image_id');
    }

    public function scopeVisible($query)
    {
        return $query->where('visibility', true);
    }
}
