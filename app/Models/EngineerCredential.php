<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\EngineerProfile;

class EngineerCredential extends Model
{
    protected $fillable = [
        'engineer_profile_id', 'name', 'oem_scope', 'admin_status', 'expires_at',
    ];

    protected $casts = ['expires_at' => 'date'];

    public function engineerProfile(): BelongsTo
    {
        return $this->belongsTo(EngineerProfile::class);
    }
}
