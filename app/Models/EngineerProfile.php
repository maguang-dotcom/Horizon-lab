<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;
use App\Models\EngineerCredential;
use App\Models\EngineerRating;
use App\Models\EngineerToolkit;

class EngineerProfile extends Model
{
    protected $fillable = [
        'user_id', 'facility_id', 'professional_title', 'specialization',
        'years_experience', 'latitude', 'longitude', 'location_updated_at',
        'bio_safety_level', 'license_number', 'certifications', 'is_available',
        'is_approved', 'approved_at',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'location_updated_at' => 'datetime',
        'bio_safety_level' => 'integer',
        'is_available' => 'boolean',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function credentials(): HasMany
    {
        return $this->hasMany(EngineerCredential::class);
    }

    public function toolkits(): HasMany
    {
        return $this->hasMany(EngineerToolkit::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(EngineerRating::class);
    }

    public function scopeDispatchEligibleFor(Builder $query, int $facilityId): Builder
    {
        if (Schema::hasColumn('engineer_profiles', 'is_approved')) {
            $query->where('is_approved', true);
        } else {
            $query->whereHas('credentials', function ($credentialsQuery) {
                $credentialsQuery->where('admin_status', 'approved');
            });
        }

        return $query->where('is_available', true)
            ->where(function (Builder $query) use ($facilityId) {
                $query->whereNull('facility_id')->orWhere('facility_id', $facilityId);
            })
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');
    }

    public function averageRating(): ?float
    {
        return $this->ratings()->avg('rating');
    }

    public function hasOemCredentialFor(string $manufacturer): bool
    {
        return $this->credentials()
            ->where('admin_status', 'approved')
            ->where(fn ($query) => $query->whereNull('oem_scope')->orWhere('oem_scope', $manufacturer))
            ->exists();
    }

    public function hasToolkitStocked(string $item): bool
    {
        return $this->toolkits()->where('item', $item)->where('in_stock', true)->exists();
    }
}
