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

class Project extends Model implements HasMedia
{
    use HasFactory, HasResponsiveMedia, InteractsWithMedia, LogsActivity, SoftDeletes {
        // Spatie ships an empty stub of this method; ours wins and generates
        // the widths listed in config('media-library.responsive_widths').
        HasResponsiveMedia::registerMediaConversions insteadof InteractsWithMedia;
    }

    protected $fillable = ['slug', 'title', 'summary', 'category', 'location', 'client', 'year', 'status', 'featured', 'area', 'floors', 'description', 'design_concept', 'architecture_info', 'structural_info', 'engineering_info', 'geotechnical_info', 'construction_info', 'interior_info', 'consultant', 'featured_image_id', 'progress_overview', 'seo', 'published_at', 'sort_order', 'locale'];

    protected $casts = [
        'year' => 'integer',
        'floors' => 'integer',
        'seo' => 'array',
        'published_at' => 'datetime',
        'sort_order' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'slug', 'category', 'location', 'status', 'published_at'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'video/mp4', 'application/pdf']);

        $this->addMediaCollection('documents')
            ->acceptsMimeTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(ProjectVideo::class)->orderBy('sort_order');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ProjectDocument::class)->orderBy('sort_order');
    }

    public function progressItems(): HasMany
    {
        return $this->hasMany(ProjectProgressItem::class)->orderBy('sort_order');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'project_service')->withTimestamps();
    }

    public function teamMembers(): BelongsToMany
    {
        return $this->belongsToMany(TeamMember::class, 'project_team_member')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProjectTranslation::class);
    }

    public function translation(string $locale): HasOne
    {
        return $this->hasOne(ProjectTranslation::class)->where('locale', $locale);
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
