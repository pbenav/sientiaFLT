<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Payment;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    protected PricingService $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            $startDate = Carbon::parse($data['start_date']);
            $endDate = Carbon::parse($data['end_date']);

            $vehicle = Vehicle::findOrFail($data['vehicle_id']);

            if (!$this->isVehicleAvailable($vehicle, $startDate, $endDate)) {
                throw new \RuntimeException('Vehicle not available for selected dates');
            }

            $pricing = $this->pricingService->calculatePrice(
                $vehicle,
                $startDate,
                $endDate,
                $data['extras'] ?? []
            );

            $bookingNumber = 'BK-' . date('Ymd') . '-' . str_pad(Booking::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            $booking = Booking::create([
                'customer_id' => $data['customer_id'],
                'vehicle_id' => $vehicle->id,
                'location_id' => $data['location_id'] ?? $vehicle->location_id,
                'booking_number' => $bookingNumber,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'start_location' => $data['start_location'] ?? null,
                'end_location' => $data['end_location'] ?? null,
                'return_location' => $data['return_location'] ?? null,
                'is_round_trip' => $data['is_round_trip'] ?? false,
                'driver_age' => $data['driver_age'] ?? null,
                'has_additional_driver' => $data['has_additional_driver'] ?? false,
                'subtotal' => $pricing['subtotal'],
                'tax_amount' => $pricing['tax_amount'],
                'total_amount' => $pricing['total'],
                'currency_code' => $data['currency_code'] ?? 'EUR',
                'deposit_amount' => $data['deposit_amount'] ?? 0,
                'special_requests' => $data['special_requests'] ?? null,
                'booking_source' => $data['booking_source'] ?? 'website',
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            if (isset($data['services'])) {
                foreach ($data['services'] as $serviceData) {
                    BookingService::create([
                        'booking_id' => $booking->id,
                        'service_type' => $serviceData['type'] ?? 'extra',
                        'name' => $serviceData['name'] ?? '',
                        'unit_price' => $serviceData['price'] ?? 0,
                        'total_price' => $serviceData['total'] ?? 0,
                        'quantity' => $serviceData['quantity'] ?? 1,
                        'calculation_type' => $serviceData['calculation_type'] ?? 'fixed',
                    ]);
                }
            }

            return $booking;
        });
    }

    public function isVehicleAvailable(Vehicle $vehicle, Carbon $startDate, Carbon $endDate): bool
    {
        $overlapping = Booking::where('vehicle_id', $vehicle->id)
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->exists();

        return !$overlapping;
    }

    public function confirmBooking(Booking $booking): Booking
    {
        return $booking->update(['status' => 'confirmed', 'is_confirmed' => true]);
    }

    public function cancelBooking(Booking $booking): Booking
    {
        return $booking->update(['status' => 'cancelled']);
    }
}
