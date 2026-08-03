<?php

namespace App\Interfaces;

use App\Models\Vehicle;
use Carbon\Carbon;

interface AvailabilityServiceInterface
{
    public function isVehicleAvailable(Vehicle $vehicle, Carbon $startDate, Carbon $endDate): bool;

    public function getAvailableVehicles(
        ?int $categoryId = null,
        ?string $type = null,
        ?string $fuelType = null,
        ?string $transmission = null,
        ?int $minSeats = null,
        ?float $minPrice = null,
        ?float $maxPrice = null,
        ?string $search = null,
        ?\DateTimeInterface $startDate = null,
        ?\DateTimeInterface $endDate = null,
        ?int $locationId = null
    );
}
