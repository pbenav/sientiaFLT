<?php

namespace App\Interfaces;

use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Booking;

interface ErpClientInterface
{
    public function syncVehicle(Vehicle $vehicle): void;

    public function syncCustomer(Customer $customer): void;

    public function syncBooking(Booking $booking): void;
}
