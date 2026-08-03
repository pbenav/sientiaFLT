<?php

namespace App\Interfaces;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use Carbon\Carbon;

interface BookingServiceInterface
{
    public function createBooking(array $data): Booking;

    public function isVehicleAvailable(Vehicle $vehicle, Carbon $startDate, Carbon $endDate): bool;

    public function confirmBooking(Booking $booking): Booking;

    public function cancelBooking(Booking $booking): Booking;

    public function completeBooking(Booking $booking): Booking;
}
