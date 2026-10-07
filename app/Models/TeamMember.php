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

class TeamMember extends Model implements HasMedia
{
    use HasFactory, HasResponsiveMedia, InteractsWithMedia, LogsActivity, SoftDeletes {
        // Spatie ships an empty stub of this method; ours wins and generates
        // the widths listed in config('media-library.responsive_widths').
        HasResponsiveMedia::registerMediaConversions insteadof InteractsWithMedia;
    }

    protected $fillable = ['slug', 'name', 'photo_id', 'designation', 'qualification', 'expertise', 'registration', 'biography', 'office_id', 'email', 'phone', 'social_links', 'sort_order', 'visibility', 'seo', 'locale'];

    protected $casts = ['social_links' => 'array', 'seo' => 'array', 'sort_order' => 'integer', 'visibility' => 'boolean'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'slug', 'designation', 'office_id', 'visibility'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->singleFile();
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function photo(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'photo_id');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_team_member')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_team_member')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function translations(): HasMany
    {
        return $this->hasMany(TeamMemberTranslation::class);
    }

    public function translation(string $locale): HasOne
    {
        return $this->hasOne(TeamMemberTranslation::class)->where('locale', $locale);
    }

    public function scopeVisible($query)
    {
        return $query->where('visibility', true);
    }

    public function scopeByOffice($query, $officeId)
    {
        return $query->where('office_id', $officeId);
    }
}
