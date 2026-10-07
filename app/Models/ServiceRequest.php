<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ServiceRequestAttachment;

class ServiceRequest extends Model
{
    protected $fillable = [
        'form_ref', 'facility_id', 'equipment_id', 'requested_by',
        'problem_classification', 'diagnostic_notes', 'urgency_tier',
        'sla_minutes', 'status', 'assigned_engineer_profile_id', 'matched_at', 'resolved_at',
    ];

    protected $casts = ['matched_at' => 'datetime', 'resolved_at' => 'datetime'];

    public function facility(): BelongsTo { return $this->belongsTo(Facility::class); }
    public function equipment(): BelongsTo { return $this->belongsTo(Equipment::class); }
    public function requester(): BelongsTo { return $this->belongsTo(User::class, 'requested_by'); }
    public function attachments(): HasMany { return $this->hasMany(ServiceRequestAttachment::class); }
    public function assignedEngineer(): BelongsTo { return $this->belongsTo(EngineerProfile::class, 'assigned_engineer_profile_id'); }
}
