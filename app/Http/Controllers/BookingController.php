<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Mail\BookingConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'pickup_date' => 'nullable|date',
            'dropoff_date' => 'nullable|date',
        ]);

        return view('booking.checkout', [
            'vehicle_id' => $request->vehicle_id,
            'pickup_date' => $request->pickup_date,
            'dropoff_date' => $request->dropoff_date
        ]);
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'nif' => 'nullable|string|max:20',
            'vehicle_id' => 'required|exists:vehicles,id',
            'pickup_date' => 'required|date',
            'dropoff_date' => 'required|date',
        ]);

        // Find or create customer
        $customer = Customer::firstOrCreate(
            ['email' => $request->email],
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'nif_cif' => $request->nif,
                'is_active' => true,
            ]
        );

        $vehicle = Vehicle::findOrFail($request->vehicle_id);
        $pickup = Carbon::parse($request->pickup_date);
        $dropoff = Carbon::parse($request->dropoff_date);
        $days = (int) max(1, $pickup->diffInDays($dropoff));
        $totalPrice = $vehicle->calculatePrice($days, $pickup);

        // Generate locator
        $locator = \App\Services\BookingNumberGenerator::generateLocator();

        $booking = Booking::create([
            'booking_number' => $locator,
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => $pickup,
            'end_date' => $dropoff,
            'total_amount' => $totalPrice,
            'deposit_amount' => $vehicle->security_deposit ?? 0,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'notes' => 'Reserva online desde la web.',
        ]);

        // Send confirmation email
        try {
            Mail::to($customer->email)->send(new BookingConfirmation($booking));
        } catch (\Exception $e) {
            // Log error, but proceed
            \Log::error('Error sending email: ' . $e->getMessage());
        }

        return redirect()->route('booking.success', $booking->id);
    }

    public function success(Booking $booking)
    {
        $booking->load(['vehicle.primaryImage', 'customer']);
        return view('booking.success', compact('booking'));
    }
}
