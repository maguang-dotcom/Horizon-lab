<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BiomedicalServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'engineer_id',
        'equipment_name',
        'equipment_model',
        'serial_number',
        'service_type',
        'urgency',
        'issue_description',
        'preferred_date',
        'status',
        'assigned_at',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'assigned_at' => 'datetime',
    ];

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function engineer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'engineer_id');
    }

    public function report(): HasOne
    {
        return $this->hasOne(ServiceReport::class, 'biomedical_service_request_id');
    }

    public const SERVICE_TYPES = [
        'repair' => 'Repair',
        'preventive_maintenance' => 'Preventive maintenance',
        'calibration' => 'Calibration',
        'installation' => 'Installation',
        'other' => 'Other',
    ];

    public const URGENCY_LEVELS = [
        'low' => 'Low — no rush',
        'normal' => 'Normal — within the week',
        'high' => 'High — within 48 hours',
        'critical' => 'Critical — equipment is down',
    ];
}
