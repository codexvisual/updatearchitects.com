<?php

namespace App\Models;

use App\Models\Concerns\HasResponsiveMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Service extends Model implements HasMedia
{
    use HasFactory, HasResponsiveMedia, InteractsWithMedia, LogsActivity, SoftDeletes {
        // Spatie ships an empty stub of this method; ours wins and generates
        // the widths listed in config('media-library.responsive_widths').
        HasResponsiveMedia::registerMediaConversions insteadof InteractsWithMedia;
    }

    protected $fillable = ['slug', 'name', 'category_id', 'parent_id', 'short_description', 'full_description', 'featured_image_id', 'image_url', 'icon', 'faq', 'seo', 'sort_order', 'visibility', 'locale'];

    protected $casts = ['faq' => 'array', 'seo' => 'array', 'sort_order' => 'integer', 'visibility' => 'boolean'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'slug', 'category_id', 'visibility', 'published_at'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('featured')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->singleFile();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Service::class, 'parent_id')->orderBy('sort_order');
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_service')->withTimestamps();
    }

    public function teamMembers(): BelongsToMany
    {
        return $this->belongsToMany(TeamMember::class, 'service_team_member')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ServiceTranslation::class);
    }

    public function translation(string $locale): HasOne
    {
        return $this->hasOne(ServiceTranslation::class)->where('locale', $locale);
    }

    public function scopeVisible($query)
    {
        return $query->where('visibility', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
}
