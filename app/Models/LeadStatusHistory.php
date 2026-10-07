<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = ['lead_id', 'from_status', 'to_status', 'user_id', 'note'];

    protected $casts = ['created_at' => 'datetime'];

    public function lead()
    {
        return $this->belongsTo(ConsultationLead::class, 'lead_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
