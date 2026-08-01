<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\Location;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    public function __invoke(): View
    {
        $compactCount = Vehicle::where('type', 'compact')->where('is_active', true)->count();
        $suvCount = Vehicle::where('type', 'suv')->where('is_active', true)->count();
        $sedanCount = Vehicle::where('type', 'sedan')->where('is_active', true)->count();
        $vanCount = Vehicle::where('type', 'van')->where('is_active', true)->count();
        $vehicleCount = Vehicle::where('is_active', true)->count();
        $customerCount = Customer::where('is_active', true)->count();
        $bookingCount = Booking::whereIn('status', ['completed', 'active'])->count();
        $locationCount = Location::where('is_active', true)->count();

        return view('welcome', compact(
            'compactCount', 'suvCount', 'sedanCount', 'vanCount',
            'vehicleCount', 'customerCount', 'bookingCount', 'locationCount'
        ));
    }
}
