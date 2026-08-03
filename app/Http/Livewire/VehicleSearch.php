<?php

namespace App\Http\Livewire;

use App\DTOs\VehicleSearchDto;
use App\Services\AvailabilityService;
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
        $dto = VehicleSearchDto::fromRequest([
            'location_id' => $this->location_id,
            'type' => $this->vehicle_type,
            'transmission' => $this->transmission,
            'fuel_type' => $this->fuel_type,
            'min_seats' => $this->passengers > 1 ? $this->passengers : null,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'search' => $this->search_term,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        $this->results = app(AvailabilityService::class)->getAvailableVehicles(
            categoryId: null,
            type: $dto->type,
            fuelType: $dto->fuelType,
            transmission: $dto->transmission,
            minSeats: $dto->minSeats,
            minPrice: $dto->minPrice,
            maxPrice: $dto->maxPrice,
            search: $dto->search,
            startDate: $dto->startDate,
            endDate: $dto->endDate,
            locationId: $dto->locationId,
        );

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
