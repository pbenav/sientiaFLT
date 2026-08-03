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
    protected PricingService $pricingService;
    protected BookingService $bookingService;

    public function boot(PricingService $pricingService, BookingService $bookingService): void
    {
        $this->pricingService = $pricingService;
        $this->bookingService = $bookingService;
    }
    public $vehicle_id;
    public $start_date;
    public $end_date;
    public $extras = [];
    public $total_price = 0;
    public $base_price = 0;
    public $discount = 0;
    public $subtotal = 0;
    public $tax_name = 'Impuestos';
    public $tax_amount = 0;
    public $duration_days = 0;
    
    // Guest data
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone = '';
    public $nif = '';
    
    public $customer_id;
    public $customer_name = '';

    public function mount($vehicle_id = null, $customer_id = null, $start_date = null, $end_date = null)
    {
        $this->vehicle_id = $vehicle_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        
        if ($customer_id) {
            $this->customer_id = $customer_id;
            $customer = Customer::find($customer_id);
            if ($customer) {
                $this->customer_name = $customer->full_name;
            }
        }
        
        if ($this->vehicle_id && $this->start_date && $this->end_date) {
            $this->calculatePrice();
        }
    }

    public function updated($property)
    {
        if (in_array($property, ['start_date', 'end_date', 'extras'])) {
            $this->calculatePrice();
        }
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

        $pricing = $this->pricingService->calculatePrice(
            $vehicle,
            $this->start_date,
            $this->end_date,
            $this->extras
        );

        $this->total_price = $pricing['total'] ?? 0;
        $this->base_price = $pricing['base_price'] ?? 0;
        $this->discount = $pricing['discount'] ?? 0;
        $this->subtotal = $pricing['subtotal'] ?? 0;
        $this->tax_name = $pricing['tax_name'] ?? 'Impuestos';
        $this->tax_amount = $pricing['tax_amount'] ?? 0;
        $this->duration_days = $pricing['duration_days'] ?? 0;
    }

    public function submit()
    {
        $this->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'nif' => 'nullable|string|max:20',
        ]);

        $vehicle = Vehicle::find($this->vehicle_id);
        if (!$vehicle) {
            session()->flash('error', 'Vehículo no encontrado.');
            return;
        }

        // Find or create customer
        $customer = Customer::firstOrCreate(
            ['email' => $this->email],
            [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'phone' => $this->phone,
                'nif_cif' => $this->nif,
                'is_active' => true,
            ]
        );
        $this->customer_id = $customer->id;

        $booking = $this->bookingService->createBooking([
            'vehicle_id' => $this->vehicle_id,
            'customer_id' => $this->customer_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'extras' => $this->extras,
            'booking_source' => 'website',
            'deposit_amount' => $vehicle->security_deposit ?? 0,
            'total_amount' => $this->total_price,
            'notes' => 'Reserva online unificada.',
        ]);

        // Send email
        try {
            \Illuminate\Support\Facades\Mail::to($customer->email)->send(new \App\Mail\BookingConfirmation($booking));
        } catch (\Exception $e) {
            \Log::error('Error sending email: ' . $e->getMessage());
        }

        return redirect()->route('booking.success', $booking->id);
    }

    public function render()
    {
        $vehicle = $this->vehicle_id ? Vehicle::find($this->vehicle_id) : null;
        $extras = VehicleExtra::where('is_active', true)->get();
        $customers = Customer::where('is_active', true)->get();

        return view('livewire.booking-form', compact('vehicle', 'extras', 'customers'));
    }
}
