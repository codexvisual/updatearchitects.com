<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class ConsultationLead extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = ['name', 'phone', 'email', 'project_type', 'project_location', 'approximate_area', 'required_services', 'estimated_budget', 'expected_start_date', 'message', 'files', 'consent', 'contact_method', 'language', 'source', 'utm', 'status', 'assigned_to', 'follow_up_at', 'notes', 'locale', 'ip_address'];

    protected $casts = ['required_services' => 'array', 'files' => 'array', 'utm' => 'array', 'consent' => 'boolean', 'expected_start_date' => 'date', 'follow_up_at' => 'datetime'];

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function statusHistory()
    {
        return $this->hasMany(LeadStatusHistory::class, 'lead_id')->orderBy('created_at', 'desc');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'phone', 'email'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['new', 'contacted', 'qualified', 'proposal_sent']);
    }
}
