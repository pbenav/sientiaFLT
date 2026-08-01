<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function form(Vehicle $vehicle, Customer $customer): View
    {
        return view('booking.form', compact('vehicle', 'customer'));
    }
}
