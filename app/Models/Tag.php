<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Tag extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['slug', 'name', 'locale'];

    public function posts()
    {
        return $this->belongsToMany(BlogPost::class, 'blog_post_tag')->withTimestamps();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['slug', 'name', 'locale'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function scopeVisible($query)
    {
        return $query->whereHas('posts', fn ($q) => $q->published());
    }
}
