<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equipment extends Model
{
    protected $fillable = [
        'facility_id', 'name', 'manufacturer', 'model',
        'serial_number', 'location_label', 'last_pm_at', 'oem_verified',
    ];

    protected $casts = [
        'last_pm_at' => 'date',
        'oem_verified' => 'boolean',
    ];

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }
}
