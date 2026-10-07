<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['office_id', 'locale', 'name', 'address'];

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
