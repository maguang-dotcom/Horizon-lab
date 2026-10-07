<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EngineerRating extends Model
{
    protected $fillable = ['engineer_profile_id', 'service_request_id', 'rating', 'comment'];

    protected $casts = ['rating' => 'integer'];

    public function engineerProfile(): BelongsTo
    {
        return $this->belongsTo(EngineerProfile::class);
    }

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }
}
