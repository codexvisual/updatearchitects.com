<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPostTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['blog_post_id', 'locale', 'title', 'excerpt', 'content', 'seo'];

    protected $casts = ['seo' => 'array'];

    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class);
    }
}
