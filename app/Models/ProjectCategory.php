<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class ProjectCategory extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['slug', 'name', 'description', 'sort_order', 'locale'];

    protected $casts = ['sort_order' => 'integer'];

    public function projects()
    {
        return $this->hasMany(Project::class, 'category', 'slug', 'slug');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['slug', 'name', 'description'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }
}
