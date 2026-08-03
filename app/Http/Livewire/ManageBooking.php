<?php

namespace App\Http\Livewire;

use App\Models\Booking;
use App\Services\PricingService;
use Carbon\Carbon;
use Livewire\Component;

class ManageBooking extends Component
{
    protected PricingService $pricingService;

    public function boot(PricingService $pricingService): void
    {
        $this->pricingService = $pricingService;
    }
    public $locator = '';
    public $email = '';
    public $booking = null;
    
    // Modification data
    public $editMode = false;
    public $new_start_date;
    public $new_end_date;
    public $new_base_price = 0;
    public $new_discount = 0;
    public $new_subtotal = 0;
    public $new_tax_name = '';
    public $new_tax_amount = 0;
    public $new_total_price = 0;
    public $new_duration_days = 0;
    
    public function search()
    {
        $this->validate([
            'locator' => 'required|string',
            'email' => 'required|email',
        ]);
        
        $this->booking = Booking::with(['vehicle.primaryImage', 'customer'])
            ->where('booking_number', $this->locator)
            ->whereHas('customer', function($q) {
                $q->where('email', $this->email);
            })->first();
            
        if (!$this->booking) {
            session()->flash('error', 'No se ha encontrado ninguna reserva con estos datos.');
            $this->editMode = false;
            return;
        }
        
        $this->new_start_date = $this->booking->start_date->format('Y-m-d');
        $this->new_end_date = $this->booking->end_date->format('Y-m-d');
        $this->recalculate();
    }
    
    public function enableEdit()
    {
        $this->editMode = true;
    }
    
    public function cancelEdit()
    {
        $this->editMode = false;
        $this->new_start_date = $this->booking->start_date->format('Y-m-d');
        $this->new_end_date = $this->booking->end_date->format('Y-m-d');
        $this->recalculate();
    }
    
    public function updatedNewStartDate()
    {
        $this->recalculate();
    }
    
    public function updatedNewEndDate()
    {
        $this->recalculate();
    }
    
    public function recalculate()
    {
        if (!$this->new_start_date || !$this->new_end_date) return;
        
        $pricing = $this->pricingService->calculatePrice(
            $this->booking->vehicle,
            $this->new_start_date,
            $this->new_end_date
        );
        
        $this->new_base_price = $pricing['base_price'];
        $this->new_discount = $pricing['discount'];
        $this->new_subtotal = $pricing['subtotal'];
        $this->new_tax_name = $pricing['tax_name'];
        $this->new_tax_amount = $pricing['tax_amount'];
        $this->new_total_price = $pricing['total'];
        $this->new_duration_days = $pricing['duration_days'];
    }
    
    public function saveModification()
    {
        $this->validate([
            'new_start_date' => 'required|date',
            'new_end_date' => 'required|date|after_or_equal:new_start_date',
        ]);
        
        $this->booking->update([
            'start_date' => $this->new_start_date,
            'end_date' => $this->new_end_date,
            'total_amount' => $this->new_total_price,
            'status' => 'pending', // Revert to pending for review if dates change
            'notes' => $this->booking->notes . "\nModificada por cliente el " . now()->format('d/m/Y'),
        ]);
        
        session()->flash('success', 'Reserva modificada correctamente.');
        $this->editMode = false;
        
        // Refresh booking data
        $this->booking->refresh();
    }

    public function render()
    {
        return view('livewire.manage-booking')
            ->extends('layouts.app')
            ->section('content');
    }
}
