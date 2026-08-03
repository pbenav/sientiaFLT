<?php

namespace App\Services;

use App\DTOs\VehicleSearchDto;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Collection;

class VehicleSearchService
{
    protected AvailabilityService $availabilityService;

    public function __construct(AvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    public function search(VehicleSearchDto $dto): Collection
    {
        $results = $this->availabilityService->getAvailableVehicles(
            categoryId: $dto->categoryId,
            type: $dto->type,
            fuelType: $dto->fuelType,
            transmission: $dto->transmission,
            minSeats: $dto->minSeats,
            minPrice: $dto->minPrice,
            maxPrice: $dto->maxPrice,
            search: $dto->search,
            startDate: $dto->startDate,
            endDate: $dto->endDate,
            locationId: $dto->locationId,
        );

        return $results;
    }

    public function searchWithSort(VehicleSearchDto $dto, string $sort = 'price_asc'): Collection
    {
        $results = $this->search($dto);

        return match ($sort) {
            'price_desc' => $results->sortBy('daily_rate', SORT_NUMERIC, true)->values(),
            'name' => $results->sortBy('name', SORT_ASC, true)->values(),
            'seats' => $results->sortBy('seats', SORT_NUMERIC, true)->values(),
            default => $results->sortBy('daily_rate', SORT_NUMERIC, true)->values(),
        };
    }
}
