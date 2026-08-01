<div>
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Search Form -->
    <form wire:submit.prevent="search" class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search Term -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    <svg class="inline h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Search
                </label>
                <input type="text" wire:model="search_term" placeholder="Brand, model, type..." class="input-field">
            </div>

            <!-- Start Date -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    <svg class="inline h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Pick-up Date
                </label>
                <input type="date" wire:model="start_date" min="{{ date('Y-m-d') }}" class="input-field">
            </div>

            <!-- End Date -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    <svg class="inline h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Return Date
                </label>
                <input type="date" wire:model="end_date" min="{{ $start_date ?? date('Y-m-d') }}" class="input-field">
            </div>

            <!-- Location -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    <svg class="inline h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Location
                </label>
                <select wire:model="location_id" class="select-field">
                    <option value="">All Locations</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Vehicle Type -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Type</label>
                <select wire:model="vehicle_type" class="select-field">
                    <option value="">All Types</option>
                    <option value="compact">Compact</option>
                    <option value="suv">SUV</option>
                    <option value="sedan">Sedan</option>
                    <option value="van">Van</option>
                    <option value="truck">Truck</option>
                </select>
            </div>

            <!-- Transmission -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Transmission</label>
                <select wire:model="transmission" class="select-field">
                    <option value="">Any</option>
                    <option value="automatic">Automatic</option>
                    <option value="manual">Manual</option>
                </select>
            </div>

            <!-- Fuel Type -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Fuel</label>
                <select wire:model="fuel_type" class="select-field">
                    <option value="">Any</option>
                    <option value="petrol">Petrol</option>
                    <option value="diesel">Diesel</option>
                    <option value="electric">Electric</option>
                    <option value="hybrid">Hybrid</option>
                </select>
            </div>

            <!-- Passengers -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Passengers</label>
                <select wire:model="passengers" class="select-field">
                    <option value="1">1+</option>
                    <option value="2">2+</option>
                    <option value="4">4+</option>
                    <option value="5">5+</option>
                    <option value="7">7+</option>
                    <option value="9">9+</option>
                </select>
            </div>

            <!-- Price Range -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Max Price/Day</label>
                <input type="number" wire:model="max_price" placeholder="€" min="0" step="5" class="input-field">
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="btn-primary text-white px-8 py-3 rounded-lg font-semibold flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Search Vehicles
            </button>
        </div>
    </form>

    <!-- Results Count -->
    @if($showResults || count($results) > 0)
    <div class="mt-6 pt-6 border-t border-gray-100">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600">
                <span class="font-bold text-gray-900">{{ count($results) }}</span> vehicles found
                @if($start_date && $end_date)
                    &middot; {{ \Carbon\Carbon::parse($start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($end_date)->format('M d') }}
                @endif
            </p>
        </div>
    </div>
    @endif
</div>
