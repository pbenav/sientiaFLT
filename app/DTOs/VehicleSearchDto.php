<?php

namespace App\DTOs;

use DateTimeInterface;

readonly class VehicleSearchDto
{
    public function __construct(
        public ?int $categoryId = null,
        public ?string $type = null,
        public ?string $fuelType = null,
        public ?string $transmission = null,
        public ?int $minSeats = null,
        public ?float $minPrice = null,
        public ?float $maxPrice = null,
        public ?string $search = null,
        public ?DateTimeInterface $startDate = null,
        public ?DateTimeInterface $endDate = null,
        public ?int $locationId = null,
    ) {}

    public static function fromRequest(array $input): self
    {
        return new self(
            categoryId: isset($input['category_id']) ? (int) $input['category_id'] : null,
            type: $input['type'] ?? null,
            fuelType: $input['fuel_type'] ?? null,
            transmission: $input['transmission'] ?? null,
            minSeats: isset($input['min_seats']) ? (int) $input['min_seats'] : null,
            minPrice: isset($input['min_price']) ? (float) $input['min_price'] : null,
            maxPrice: isset($input['max_price']) ? (float) $input['max_price'] : null,
            search: $input['search'] ?? null,
            startDate: isset($input['start_date'])
                ? \Carbon\Carbon::parse($input['start_date'])
                : null,
            endDate: isset($input['end_date'])
                ? \Carbon\Carbon::parse($input['end_date'])
                : null,
            locationId: isset($input['location_id']) ? (int) $input['location_id'] : null,
        );
    }
}
