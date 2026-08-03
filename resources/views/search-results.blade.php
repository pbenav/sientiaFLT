@extends('layouts.app')

@section('content')
<section class="ex-section" style="background: #F4F5F6; min-height: calc(100vh - 200px);">
    <div class="container-ex">
        
        <!-- Header Banner -->
        <div style="background: linear-gradient(135deg, #161829 0%, #292D45 100%); padding: 60px 0; margin: 0 -20px 40px -20px;">
            <div style="max-width: 1200px; margin: 0 auto; text-align: center; padding: 0 20px;">
                <h1 style="font-size: 40px; font-weight: 800; color: #ffffff; margin-bottom: 15px; font-family: 'Space Grotesk', sans-serif;">{{ __('Nuestra Flota') }}</h1>
                <p style="font-size: 16px; color: #94a3b8; max-width: 600px; margin: 0 auto;">
                    @if(isset($totalResults))
                        {{ __('Hemos encontrado') }} <strong>{{ $totalResults }}</strong> {{ __('vehículos disponibles para tus fechas.') }}
                    @else
                        {{ __('Descubre nuestra selección de vehículos premium, listos para tu aventura.') }}
                    @endif
                </p>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Filters Sidebar -->
            <div class="lg:w-72 flex-shrink-0">
                <div class="ex-card lg:sticky lg:top-24 mb-6">
                    <div class="ex-card-body">
                        <h3 class="font-bold text-gray-900 mb-5 flex items-center" style="font-family: 'Space Grotesk', sans-serif; font-size: 1.2rem;">
                            <svg class="h-5 w-5 mr-2 text-ex-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            {{ __('Filtros') }}
                        </h3>

                    <!-- Fechas Resumen -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg text-sm text-gray-600 border border-gray-100">
                        <div class="flex justify-between mb-2">
                            <span class="font-bold">{{ __('Recogida:') }}</span>
                            <span>{{ $pickup->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-bold">{{ __('Devolución:') }}</span>
                            <span>{{ $dropoff->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <!-- Vehicle Type -->
                    <div class="mb-6">
                        <h4 class="text-sm font-bold text-gray-800 mb-3 uppercase tracking-wide">{{ __('Tipo de Vehículo') }}</h4>
                        <div class="space-y-2">
                            @foreach(\App\Models\VehicleCategory::where('is_active', true)->get() as $category)
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" value="{{ $category->id }}" class="category-filter rounded border-gray-300 text-ex-accent focus:ring-ex-accent transition-colors"
                                    {{ in_array((string)$category->id, explode(',', request('categories', ''))) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600 group-hover:text-ex-primary transition-colors">{{ $category->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Transmission -->
                    <div class="mb-6">
                        <h4 class="text-sm font-bold text-gray-800 mb-3 uppercase tracking-wide">{{ __('Transmisión') }}</h4>
                        <div class="space-y-2">
                            @foreach(['automatic' => __('Automático'), 'manual' => __('Manual')] as $trans => $label)
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" value="{{ $trans }}" class="transmission-filter rounded border-gray-300 text-ex-accent focus:ring-ex-accent transition-colors">
                                <span class="ml-2 text-sm text-gray-600 group-hover:text-ex-primary transition-colors">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                        <button onclick="applyFilters()" class="ex-btn ex-btn-primary w-full mb-3 shadow-md hover:shadow-lg">
                            {{ __('Aplicar Filtros') }}
                        </button>
                        <button onclick="resetFilters()" class="ex-btn ex-btn-white border border-gray-200 w-full text-gray-600">
                            {{ __('Restablecer') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Results Grid -->
            <div class="flex-1">
                <!-- Toolbar -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-6 flex items-center justify-between">
                    <span class="text-gray-500 text-sm">
                        <span class="font-bold text-gray-900">{{ count($results) }}</span> {{ __('vehículos mostrados') }}
                    </span>
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-700 hidden sm:inline-block">{{ __('Ordenar:') }}</span>
                        <select class="ex-select py-2 pl-3 pr-8 text-sm bg-gray-50 border-gray-200" style="width: auto; min-width: 180px;" onchange="sortResults(this.value)">
                            <option value="price_asc">{{ __('Precio: Menor a Mayor') }}</option>
                            <option value="price_desc">{{ __('Precio: Mayor a Menor') }}</option>
                            <option value="name">{{ __('Nombre: A-Z') }}</option>
                        </select>
                    </div>
                </div>

                @if(isset($results) && count($results) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($results as $vehicle)
                        <div class="ex-card flex flex-col h-full bg-white shadow-sm hover:shadow-xl transition-all duration-300">
                            <!-- Image Container -->
                            <div class="relative h-48 overflow-hidden group">
                                @if($vehicle->primaryImage && $vehicle->primaryImage->url)
                                    <img src="{{ $vehicle->primaryImage->url }}" alt="{{ $vehicle->name }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7a2 2 0 11-4 0 2 2 0 014 0z M9 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                @endif

                                <!-- Badges overlay -->
                                <div class="absolute top-3 left-3 flex flex-col gap-2">
                                    @if($vehicle->is_electric)
                                        <span class="bg-green-500 text-white text-xs font-bold px-2.5 py-1 rounded-sm shadow-sm uppercase tracking-wide">{{ __('Eco') }}</span>
                                    @endif
                                    @if($vehicle->is_new)
                                        <span class="bg-blue-600 text-white text-xs font-bold px-2.5 py-1 rounded-sm shadow-sm uppercase tracking-wide">{{ __('Nuevo') }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="ex-card-body flex-1 flex flex-col p-5">
                                <div class="mb-3">
                                    <h3 class="ex-card-title text-xl mb-1">{{ $vehicle->name }}</h3>
                                    <p class="text-gray-500 text-sm font-medium">{{ $vehicle->brand }} - {{ $vehicle->model }}</p>
                                </div>

                                <!-- Features Grid -->
                                <div class="grid grid-cols-2 gap-y-2 mb-4 mt-auto border-t border-b border-gray-100 py-3">
                                    @if($vehicle->seats)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="h-4 w-4 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        {{ $vehicle->seats }} {{ __('plazas') }}
                                    </div>
                                    @endif
                                    
                                    @if($vehicle->transmission)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="h-4 w-4 mr-1.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ ucfirst($vehicle->transmission) }}
                                    </div>
                                    @endif
                                </div>

                                <!-- Booking Action -->
                                <div class="mt-2">
                                    <div class="flex items-baseline mb-3">
                                        <span class="ex-card-price text-2xl mr-2">€{{ number_format($vehicle->calculatePrice($days, $pickup), 2) }}</span>
                                        <span class="text-sm text-gray-400 font-medium">{{ __('Total por') }} {{ $days }} {{ $days == 1 ? __('día') : __('días') }}</span>
                                    </div>
                                    
                                    <form action="{{ route('booking.checkout') }}" method="GET" class="m-0">
                                        <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                                        <input type="hidden" name="pickup_date" value="{{ $pickup->format('Y-m-d') }}">
                                        <input type="hidden" name="dropoff_date" value="{{ $dropoff->format('Y-m-d') }}">
                                        <button type="submit" class="ex-btn ex-btn-primary w-full flex items-center justify-center group shadow-md hover:shadow-lg">
                                            {{ __('Reservar Ahora') }}
                                            <svg class="h-4 w-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-16 text-center">
                        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-gray-100 mb-6">
                            <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3" style="font-family: 'Space Grotesk', sans-serif;">{{ __('No se encontraron vehículos') }}</h3>
                        <p class="text-gray-500 mb-8 max-w-md mx-auto">{{ __('No tenemos vehículos disponibles que coincidan con tus fechas y filtros. Prueba a cambiar las fechas o los criterios de búsqueda.') }}</p>
                        <a href="/" class="ex-btn ex-btn-primary inline-flex items-center">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            {{ __('Volver al inicio') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function applyFilters() {
        const categories = Array.from(document.querySelectorAll('.category-filter:checked')).map(el => el.value);
        const transmissions = Array.from(document.querySelectorAll('.transmission-filter:checked')).map(el => el.value);
        
        // Mantener las fechas en la URL
        const urlParams = new URLSearchParams(window.location.search);
        
        if (categories.length) urlParams.set('categories', categories.join(','));
        else urlParams.delete('categories');
        
        if (transmissions.length) urlParams.set('transmissions', transmissions.join(','));
        else urlParams.delete('transmissions');
        
        window.location.search = urlParams.toString();
    }

    function resetFilters() {
        document.querySelectorAll('.category-filter, .transmission-filter').forEach(el => el.checked = false);
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.delete('categories');
        urlParams.delete('transmissions');
        window.location.search = urlParams.toString();
    }

    function sortResults(value) {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('sort', value);
        window.location.search = urlParams.toString();
    }
</script>
@endpush
@endsection
