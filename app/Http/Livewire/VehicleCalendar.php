<?php

namespace App\Http\Livewire;

use App\Models\Vehicle;
use Livewire\Component;

class VehicleCalendar extends Component
{
    public $vehicle_id;
    public $bookings = [];
    public $vehicle;
    public $month;
    public $year;

    public function mount($vehicle_id = null)
    {
        $this->vehicle_id = $vehicle_id;
        $this->month = now()->month;
        $this->year = now()->year;

        if ($vehicle_id) {
            $this->loadBookings();
        }
    }

    public function loadBookings()
    {
        $this->vehicle = Vehicle::find($this->vehicle_id);
        if (!$this->vehicle) {
            return;
        }

        $startDate = \Carbon\Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $endDate = \Carbon\Carbon::create($this->year, $this->month, 1)->endOfMonth();

        $this->bookings = $this->vehicle->bookings()
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->with('customer')
            ->get();
    }

    public function previousMonth()
    {
        $this->month--;
        if ($this->month < 1) {
            $this->month = 12;
            $this->year--;
        }
        $this->loadBookings();
    }

    public function nextMonth()
    {
        $this->month++;
        if ($this->month > 12) {
            $this->month = 1;
            $this->year++;
        }
        $this->loadBookings();
    }

    public function render()
    {
        return view('livewire.vehicle-calendar');
    }
}
