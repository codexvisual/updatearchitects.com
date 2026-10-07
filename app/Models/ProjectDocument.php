<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['project_id', 'media_id', 'title', 'description', 'sort_order', 'is_public'];

    protected $casts = ['sort_order' => 'integer', 'is_public' => 'boolean'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }
}
