<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class ContactMessage extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['name', 'email', 'phone', 'subject', 'message', 'status', 'read_at', 'replied_at', 'locale', 'ip_address'];

    protected $casts = ['read_at' => 'datetime', 'replied_at' => 'datetime'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'phone'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
