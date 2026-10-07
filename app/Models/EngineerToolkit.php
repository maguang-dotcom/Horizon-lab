<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\EngineerProfile;

class EngineerToolkit extends Model
{
    protected $fillable = ['engineer_profile_id', 'item', 'in_stock', 'checked_at'];

    protected $casts = ['in_stock' => 'boolean', 'checked_at' => 'datetime'];

    public function engineerProfile(): BelongsTo
    {
        return $this->belongsTo(EngineerProfile::class);
    }
}
