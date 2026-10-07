<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['setting_id', 'locale', 'value', 'description'];

    protected $casts = ['value' => 'array'];

    public function setting()
    {
        return $this->belongsTo(Setting::class);
    }
}
