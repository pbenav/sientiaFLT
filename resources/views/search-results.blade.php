@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li><a href="/" class="text-gray-400 hover:text-gray-500">Home</a></li>
            <li><svg class="h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
            <li><span class="text-gray-900 font-medium">Search Results</span></li>
        </ol>
    </nav>

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Filters Sidebar -->
        <div class="lg:w-64 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-20">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filters
                </h3>

                <!-- Vehicle Type -->
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Vehicle Type</h4>
                    <div class="space-y-2">
                        @foreach(['compact' => 'Compact', 'suv' => 'SUV', 'sedan' => 'Sedan', 'van' => 'Van', 'truck' => 'Truck'] as $type => $label)
                        <label class="flex items-center">
                            <input type="checkbox" value="{{ $type }}" class="type-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Transmission -->
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Transmission</h4>
                    <div class="space-y-2">
                        @foreach(['automatic' => 'Automatic', 'manual' => 'Manual'] as $trans => $label)
                        <label class="flex items-center">
                            <input type="checkbox" value="{{ $trans }}" class="transmission-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Fuel Type -->
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Fuel Type</h4>
                    <div class="space-y-2">
                        @foreach(['petrol' => 'Petrol', 'diesel' => 'Diesel', 'electric' => 'Electric', 'hybrid' => 'Hybrid'] as $fuel => $label)
                        <label class="flex items-center">
                            <input type="checkbox" value="{{ $fuel }}" class="fuel-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range -->
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Price per day</h4>
                    <div class="flex items-center space-x-2">
                        <input type="number" placeholder="Min" class="input-field text-sm" id="min-price">
                        <span class="text-gray-400">-</span>
                        <input type="number" placeholder="Max" class="input-field text-sm" id="max-price">
                    </div>
                </div>

                <button onclick="applyFilters()" class="btn-primary text-white w-full py-2 rounded-lg text-sm font-medium">
                    Apply Filters
                </button>
                <button onclick="resetFilters()" class="text-gray-500 w-full py-2 mt-2 text-sm hover:text-gray-700">
                    Reset
                </button>
            </div>
        </div>

        <!-- Results -->
        <div class="flex-1">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    Search Results
                    @if(isset($totalResults))
                        <span class="text-lg font-normal text-gray-500">({{ $totalResults }} vehicles)</span>
                    @endif
                </h1>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Sort by:</span>
                    <select class="select-field text-sm py-1.5" onchange="sortResults(this.value)">
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="name">Name: A-Z</option>
                        <option value="rating">Rating</option>
                    </select>
                </div>
            </div>

            @if(isset($results) && count($results) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($results as $vehicle)
                    <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Vehicle Image -->
                        <div class="relative">
                            @if($vehicle->primaryImage && $vehicle->primaryImage->url)
                                <img src="{{ $vehicle->primaryImage->url }}" alt="{{ $vehicle->name }}" class="vehicle-card-image">
                            @else
                                <div class="vehicle-card-image bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                    <svg class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 7a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            @endif
                            <!-- Badges -->
                            <div class="absolute top-3 left-3 flex flex-wrap gap-1">
                                @if($vehicle->is_electric)
                                    <span class="badge-type bg-green-500 text-white">Electric</span>
                                @endif
                                @if($vehicle->is_new)
                                    <span class="badge-type bg-blue-500 text-white">New</span>
                                @endif
                                @if($vehicle->is_featured)
                                    <span class="badge-type bg-yellow-500 text-white">Featured</span>
                                @endif
                            </div>
                            <!-- Favorite -->
                            <button class="absolute top-3 right-3 p-2 bg-white/80 rounded-full hover:bg-white transition-colors">
                                <svg class="h-5 w-5 text-gray-400 hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Vehicle Info -->
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h3 class="font-bold text-gray-900 text-lg">{{ $vehicle->name }}</h3>
                                    <p class="text-gray-500 text-sm">{{ $vehicle->brand }} - {{ $vehicle->model }} ({{ $vehicle->year }})</p>
                                </div>
                            </div>

                            <!-- Vehicle Specs -->
                            <div class="flex items-center gap-3 mb-3 text-xs text-gray-500">
                                @if($vehicle->seats)
                                <span class="flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    {{ $vehicle->seats }} seats
                                </span>
                                @endif
                                @if($vehicle->transmission)
                                <span class="flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ ucfirst($vehicle->transmission) }}
                                </span>
                                @endif
                                @if($vehicle->fuel_type)
                                <span class="flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    {{ ucfirst($vehicle->fuel_type) }}
                                </span>
                                @endif
                            </div>

                            <!-- Location -->
                            @if($vehicle->location)
                            <div class="flex items-center text-xs text-gray-400 mb-3">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $vehicle->location->name }}
                            </div>
                            @endif

                            <!-- Price & CTA -->
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                <div>
                                    <span class="text-2xl font-bold text-blue-600">€{{ number_format($vehicle->daily_rate, 2) }}</span>
                                    <span class="text-gray-400 text-sm">/day</span>
                                </div>
                                <a href="/book/{{ $vehicle->id }}/{{ auth()->id() ?? 1 }}" class="btn-primary text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-white rounded-xl border border-gray-100">
                    <svg class="h-16 w-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No vehicles found</h3>
                    <p class="text-gray-500 mb-6">Try adjusting your search criteria or filters</p>
                    <a href="/" class="btn-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium inline-block">
                        Back to Search
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function applyFilters() {
        const types = Array.from(document.querySelectorAll('.type-filter:checked')).map(el => el.value);
        const transmissions = Array.from(document.querySelectorAll('.transmission-filter:checked')).map(el => el.value);
        const fuels = Array.from(document.querySelectorAll('.fuel-filter:checked')).map(el => el.value);
        const minPrice = document.getElementById('min-price').value;
        const maxPrice = document.getElementById('max-price').value;

        let url = '/search?';
        if (types.length) url += 'types=' + types.join(',') + '&';
        if (transmissions.length) url += 'transmissions=' + transmissions.join(',') + '&';
        if (fuels.length) url += 'fuels=' + fuels.join(',') + '&';
        if (minPrice) url += 'min_price=' + minPrice + '&';
        if (maxPrice) url += 'max_price=' + maxPrice + '&';

        window.location.href = url;
    }

    function resetFilters() {
        document.querySelectorAll('.type-filter, .transmission-filter, .fuel-filter').forEach(el => el.checked = false);
        document.getElementById('min-price').value = '';
        document.getElementById('max-price').value = '';
        applyFilters();
    }

    function sortResults(value) {
        window.location.href = '/search?sort=' + value;
    }
</script>
@endpush
@endsection
