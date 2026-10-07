<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Page extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['slug', 'title', 'content_blocks', 'seo', 'status', 'published_at', 'sort_order', 'locale'];

    protected $casts = ['content_blocks' => 'array', 'seo' => 'array', 'published_at' => 'datetime', 'sort_order' => 'integer'];

    public function translations()
    {
        return $this->hasMany(PageTranslation::class);
    }

    public function translation(string $locale): HasOne
    {
        return $this->hasOne(PageTranslation::class)->where('locale', $locale);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['slug', 'title', 'content_blocks'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}
