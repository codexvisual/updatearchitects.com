<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class ProjectProgressItem extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['project_id', 'title', 'slug', 'description', 'date', 'status', 'is_completed', 'images', 'video_id', 'sort_order', 'visibility', 'internal_notes', 'locale'];

    protected $casts = ['date' => 'date', 'images' => 'array', 'is_completed' => 'boolean', 'visibility' => 'boolean', 'sort_order' => 'integer'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function video()
    {
        return $this->belongsTo(Media::class, 'video_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['project_id', 'title', 'slug'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeVisible($query)
    {
        return $query->where('visibility', true);
    }
}
