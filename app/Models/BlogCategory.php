<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class BlogCategory extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['slug', 'name', 'description', 'sort_order', 'locale'];

    protected $casts = ['sort_order' => 'integer'];

    public function posts()
    {
        return $this->hasMany(BlogPost::class, 'category_id')->orderBy('published_at', 'desc');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['slug', 'name', 'description'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function scopeVisible($query)
    {
        return $query->whereHas('posts', fn ($q) => $q->published());
    }
}
