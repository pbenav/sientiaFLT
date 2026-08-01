<?php

namespace App\Http\Livewire;

use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\VehicleExtra;
use App\Services\BookingService;
use App\Services\PricingService;
use Livewire\Component;

class BookingForm extends Component
{
    public $vehicle_id;
    public $start_date;
    public $end_date;
    public $customer_id;
    public $extras = [];
    public $total_price = 0;
    public $subtotal = 0;
    public $tax_amount = 0;
    public $duration_days = 0;
    public $customer_name = '';

    public function mount($vehicle_id = null, $customer_id = null)
    {
        $this->vehicle_id = $vehicle_id;
        if ($customer_id) {
            $this->customer_id = $customer_id;
            $customer = Customer::find($customer_id);
            if ($customer) {
                $this->customer_name = $customer->full_name;
            }
        }
    }

    public function updatedStart_date()
    {
        $this->calculatePrice();
    }

    public function updatedEnd_date()
    {
        $this->calculatePrice();
    }

    public function updatedExtras()
    {
        $this->calculatePrice();
    }

    public function calculatePrice()
    {
        if (!$this->vehicle_id || !$this->start_date || !$this->end_date) {
            return;
        }

        $vehicle = Vehicle::find($this->vehicle_id);
        if (!$vehicle) {
            return;
        }

        $pricingService = app(PricingService::class);
        $pricing = $pricingService->calculatePrice(
            $vehicle,
            $this->start_date,
            $this->end_date,
            $this->extras
        );

        $this->total_price = $pricing['total'];
        $this->subtotal = $pricing['subtotal'] ?? 0;
        $this->tax_amount = $pricing['tax'] ?? 0;
        $this->duration_days = $pricing['duration_days'] ?? 0;
    }

    public function submit()
    {
        $this->validate([
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'customer_id' => 'required|exists:customers,id',
        ]);

        $vehicle = Vehicle::find($this->vehicle_id);
        if (!$vehicle) {
            session()->flash('error', 'Vehicle not found');
            return;
        }

        $customer = Customer::find($this->customer_id);
        if (!$customer) {
            session()->flash('error', 'Customer not found');
            return;
        }

        $bookingService = app(BookingService::class);

        $booking = $bookingService->createBooking([
            'vehicle_id' => $this->vehicle_id,
            'customer_id' => $this->customer_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'extras' => $this->extras,
            'booking_source' => 'website',
        ]);

        session()->flash('message', 'Booking created successfully! Booking number: ' . $booking->booking_number);

        return redirect()->route('home');
    }

    public function render()
    {
        $vehicle = $this->vehicle_id ? Vehicle::find($this->vehicle_id) : null;
        $extras = VehicleExtra::where('is_active', true)->get();
        $customers = Customer::where('is_active', true)->get();

        return view('livewire.booking-form', compact('vehicle', 'extras', 'customers'));
    }
}
