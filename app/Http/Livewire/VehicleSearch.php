<?php

namespace App\Http\Livewire;

use App\Models\Vehicle;
use App\Models\Location;
use Livewire\Component;

class VehicleSearch extends Component
{
    public $start_date;
    public $end_date;
    public $location_id;
    public $vehicle_type;
    public $passengers = 1;
    public $transmission;
    public $fuel_type;
    public $min_price;
    public $max_price;
    public $search_term;

    public $results = [];
    public $showResults = false;

    public function search()
    {
        $query = Vehicle::active()->available();

        if ($this->location_id) {
            $query->where('location_id', $this->location_id);
        }

        if ($this->vehicle_type) {
            $query->where('type', $this->vehicle_type);
        }

        if ($this->transmission) {
            $query->where('transmission', $this->transmission);
        }

        if ($this->fuel_type) {
            $query->where('fuel_type', $this->fuel_type);
        }

        if ($this->passengers > 1) {
            $query->where('seats', '>=', $this->passengers);
        }

        if ($this->min_price) {
            $query->where('daily_rate', '>=', $this->min_price);
        }

        if ($this->max_price) {
            $query->where('daily_rate', '<=', $this->max_price);
        }

        if ($this->search_term) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search_term}%")
                    ->orWhere('brand', 'like', "%{$this->search_term}%")
                    ->orWhere('model', 'like', "%{$this->search_term}%");
            });
        }

        $this->results = $query->with(['location', 'primaryImage'])->get();

        return redirect()->route('search.results', [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'location_id' => $this->location_id,
            'vehicle_type' => $this->vehicle_type,
            'passengers' => $this->passengers,
            'transmission' => $this->transmission,
            'fuel_type' => $this->fuel_type,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'search_term' => $this->search_term,
            'results' => $this->results->map(fn($v) => $v->id)->toArray(),
        ]);
    }

    public function render()
    {
        $locations = Location::where('is_active', true)->get();
        return view('livewire.vehicle-search', compact('locations'));
    }
}
