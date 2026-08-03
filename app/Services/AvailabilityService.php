<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Collection;

class AvailabilityService implements \App\Interfaces\AvailabilityServiceInterface
{
    /**
     * Get vehicles available between two dates.
     * Uses the same logic everywhere to avoid duplicates.
     */
    public function getAvailableVehicles(
        ?int $categoryId = null,
        ?string $type = null,
        ?string $fuelType = null,
        ?string $transmission = null,
        ?int $minSeats = null,
        ?float $minPrice = null,
        ?float $maxPrice = null,
        ?string $search = null,
        \DateTimeInterface $startDate = null,
        \DateTimeInterface $endDate = null,
        ?int $locationId = null
    ): Collection {
        $query = Vehicle::query()
            ->active()
            ->available()
            ->with(['category', 'location', 'primaryImage']);

        // Filter by category
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Filter by type
        if ($type) {
            $query->where('type', $type);
        }

        // Filter by fuel type
        if ($fuelType) {
            $query->where('fuel_type', $fuelType);
        }

        // Filter by transmission
        if ($transmission) {
            $query->where('transmission', $transmission);
        }

        // Filter by minimum seats
        if ($minSeats) {
            $query->where('seats', '>=', $minSeats);
        }

        // Filter by price range
        if ($minPrice !== null || $maxPrice !== null) {
            $min = $minPrice ?? 0;
            $max = $maxPrice ?? PHP_FLOAT_MAX;
            $query->whereBetween('daily_rate', [$min, $max]);
        }

        // Search by name/model/brand
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        // Filter by location
        if ($locationId) {
            $query->where('location_id', $locationId);
        }

        // Filter by availability between dates
        if ($startDate && $endDate) {
            $query->whereDoesntHave('bookings', function ($q) use ($startDate, $endDate) {
                $q->whereIn('status', [
                    BookingStatus::Pending->value,
                    BookingStatus::Confirmed->value,
                    BookingStatus::Active->value,
                ])
                    ->where('start_date', '<=', $endDate)
                    ->where('end_date', '>=', $startDate);
            });
        }

        return $query->get();
    }

    /**
     * Check if a specific vehicle is available between two dates.
     */
    public function isVehicleAvailable(
        Vehicle $vehicle,
        \DateTimeInterface $startDate,
        \DateTimeInterface $endDate
    ): bool {
        $conflictingBookings = $vehicle->bookings()
            ->whereIn('status', [
                BookingStatus::Pending->value,
                BookingStatus::Confirmed->value,
                BookingStatus::Active->value,
            ])
            ->where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
            ->exists();

        return ! $conflictingBookings;
    }

    /**
     * Get all conflicting bookings for a vehicle within a date range.
     */
    public function getConflictingBookings(
        Vehicle $vehicle,
        \DateTimeInterface $startDate,
        \DateTimeInterface $endDate
    ): Collection {
        return $vehicle->bookings()
            ->whereIn('status', [
                BookingStatus::Pending->value,
                BookingStatus::Confirmed->value,
                BookingStatus::Active->value,
            ])
            ->where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
            ->with('customer')
            ->get();
    }

    /**
     * Get all bookings for a vehicle within a date range (for calendar display).
     */
    public function getVehicleBookings(
        Vehicle $vehicle,
        \DateTimeInterface $startDate,
        \DateTimeInterface $endDate
    ): Collection {
        return $vehicle->bookings()
            ->whereIn('status', [
                BookingStatus::Pending->value,
                BookingStatus::Confirmed->value,
                BookingStatus::Active->value,
            ])
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->with('customer')
            ->get();
    }
}
