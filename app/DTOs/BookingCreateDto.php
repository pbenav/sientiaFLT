<?php

namespace App\DTOs;

use App\Enums\BookingSource;
use App\Enums\PaymentMethod;
use DateTimeInterface;

readonly class BookingCreateDto
{
    public function __construct(
        public int $vehicleId,
        public int $customerId,
        public DateTimeInterface $startDate,
        public DateTimeInterface $endDate,
        public string $status = 'pending',
        public ?string $notes = null,
        public ?PaymentMethod $paymentMethod = null,
        public ?BookingSource $source = null,
        public array $extras = [],
        public array $additionalServices = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            vehicleId: (int) ($data['vehicle_id'] ?? 0),
            customerId: (int) ($data['customer_id'] ?? 0),
            startDate: $data['start_date'] instanceof DateTimeInterface
                ? $data['start_date']
                : \Carbon\Carbon::parse($data['start_date'] ?? now()),
            endDate: $data['end_date'] instanceof DateTimeInterface
                ? $data['end_date']
                : \Carbon\Carbon::parse($data['end_date'] ?? now()->addDay()),
            status: $data['status'] ?? 'pending',
            notes: $data['notes'] ?? null,
            paymentMethod: isset($data['payment_method'])
                ? PaymentMethod::tryFrom($data['payment_method'])
                : null,
            source: isset($data['source'])
                ? BookingSource::tryFrom($data['source'])
                : null,
            extras: $data['extras'] ?? [],
            additionalServices: $data['additional_services'] ?? [],
        );
    }
}
