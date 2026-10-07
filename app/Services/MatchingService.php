<?php

namespace App\Services;

use App\Models\EngineerProfile;
use App\Models\Equipment;
use App\Models\Facility;
use App\Models\ServiceRequest;
use Illuminate\Support\Collection;

class MatchingService
{
    public function match(ServiceRequest $request, int $limit = 5): Collection
    {
        $facility = $request->facility;
        $equipment = $request->equipment;

        return EngineerProfile::dispatchEligibleFor($facility->id)
            ->with(['user', 'credentials', 'toolkits'])
            ->get()
            ->map(fn (EngineerProfile $engineer) => $this->score($engineer, $facility, $equipment))
            ->filter(fn (array $result) => $result['criteria']['oem_certification']['passed']
                && $result['criteria']['bio_safety']['passed'])
            ->sortByDesc('composite_score')
            ->take($limit)
            ->values();
    }

    private function score(EngineerProfile $engineer, Facility $facility, Equipment $equipment): array
    {
        $distanceKm = $this->haversineKm(
            (float) $facility->latitude,
            (float) $facility->longitude,
            (float) $engineer->latitude,
            (float) $engineer->longitude,
        );

        $criteria = [
            'oem_certification' => [
                'label' => "{$equipment->manufacturer} OEM Certification",
                'passed' => $engineer->hasOemCredentialFor($equipment->manufacturer),
            ],
            'toolkit' => [
                'label' => 'SpO2 / ECG Sensor Toolkit Stocked',
                'passed' => $engineer->hasToolkitStocked('SpO2 / ECG Sensor Toolkit'),
            ],
            'bio_safety' => [
                'label' => 'ICU Bio-Safety Clearance',
                'passed' => $engineer->bio_safety_level >= 3,
                'level' => $engineer->bio_safety_level,
            ],
            'proximity' => [
                'label' => 'Proximity Threshold (< 5km)',
                'passed' => $distanceKm <= 5,
                'distance_km' => round($distanceKm, 1),
            ],
        ];

        $passedCount = collect($criteria)->where('passed', true)->count();

        return [
            'engineer' => $engineer,
            'criteria' => $criteria,
            'composite_score' => $this->compositeScore($passedCount, $distanceKm, $engineer->averageRating() ?? 0),
            'estimated_sla_minutes' => $this->estimateSlaMinutes($distanceKm),
        ];
    }

    private function compositeScore(int $passedCount, float $distanceKm, float $averageRating): float
    {
        return ($passedCount * 25) + ($averageRating * 10) - ($distanceKm * 2);
    }

    private function estimateSlaMinutes(float $distanceKm): int
    {
        return (int) round(5 + ($distanceKm * 8));
    }

    private function haversineKm(float $latitudeOne, float $longitudeOne, float $latitudeTwo, float $longitudeTwo): float
    {
        $earthRadius = 6371;
        $latitudeDelta = deg2rad($latitudeTwo - $latitudeOne);
        $longitudeDelta = deg2rad($longitudeTwo - $longitudeOne);
        $a = sin($latitudeDelta / 2) ** 2
            + cos(deg2rad($latitudeOne)) * cos(deg2rad($latitudeTwo)) * sin($longitudeDelta / 2) ** 2;

        return $earthRadius * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    }
}
