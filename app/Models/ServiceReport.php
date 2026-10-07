<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceReport extends Model
{
    protected $fillable = [
        'biomedical_service_request_id', 'engineer_id', 'problem_found', 'work_done',
        'labor_hours', 'hourly_rate', 'labor_cost', 'parts_cost', 'total_cost',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(BiomedicalServiceRequest::class, 'biomedical_service_request_id');
    }

    public function parts(): HasMany
    {
        return $this->hasMany(ServiceReportPart::class);
    }

    public function engineer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'engineer_id');
    }
}
