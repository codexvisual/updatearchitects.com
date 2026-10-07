<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'locale', 'name', 'short_description', 'full_description', 'faq', 'seo'];

    protected $casts = ['faq' => 'array', 'seo' => 'array'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
