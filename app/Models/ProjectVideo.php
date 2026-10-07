<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProjectVideo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['project_id', 'media_id', 'title', 'description', 'sort_order', 'poster_image_id'];

    protected $casts = ['sort_order' => 'integer'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function posterImage()
    {
        return $this->belongsTo(Media::class, 'poster_image_id');
    }
}
