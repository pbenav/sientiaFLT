<?php

namespace App\Interfaces;

use App\Models\Vehicle;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;

interface PricingServiceInterface
{
    public function calculatePrice(Vehicle $vehicle, mixed $startDate, mixed $endDate, array $extras = []): array;

    /**
     * Get available vehicles matching search criteria.
     */
    public function searchVehicles(
        ?int $categoryId = null,
        ?string $type = null,
        ?string $fuelType = null,
        ?string $transmission = null,
        ?int $minSeats = null,
        ?float $minPrice = null,
        ?float $maxPrice = null,
        ?string $search = null,
        ?DateTimeInterface $startDate = null,
        ?DateTimeInterface $endDate = null,
        ?int $locationId = null
    ): Collection;
}
