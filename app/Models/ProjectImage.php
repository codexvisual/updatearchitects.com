<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProjectImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['project_id', 'media_id', 'alt', 'caption', 'sort_order', 'focal_point'];

    protected $casts = ['sort_order' => 'integer', 'focal_point' => 'array'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }
}
