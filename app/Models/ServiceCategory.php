<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class ServiceCategory extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['slug', 'name', 'description', 'sort_order', 'locale'];

    protected $casts = ['sort_order' => 'integer'];

    public function services()
    {
        return $this->hasMany(Service::class, 'category_id')->orderBy('sort_order');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['slug', 'name', 'description'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }
}
