<?php

namespace App\DTOs;

use App\Models\Vehicle;
use DateTimeInterface;

readonly class PricingRequestDto
{
    public function __construct(
        public Vehicle $vehicle,
        public DateTimeInterface $startDate,
        public DateTimeInterface $endDate,
        public array $extras = [],
    ) {}

    public static function fromArray(Vehicle $vehicle, array $data): self
    {
        return new self(
            vehicle: $vehicle,
            startDate: $data['start_date'] instanceof DateTimeInterface
                ? $data['start_date']
                : \Carbon\Carbon::parse($data['start_date'] ?? now()),
            endDate: $data['end_date'] instanceof DateTimeInterface
                ? $data['end_date']
                : \Carbon\Carbon::parse($data['end_date'] ?? now()->addDay()),
            extras: $data['extras'] ?? [],
        );
    }
}
