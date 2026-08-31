{{-- This file is included by pages/show.blade.php when template='inicio' --}}

{{-- Flash Messages --}}
@if(session('message'))
<div id="flash-message" class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mx-auto max-w-7xl mt-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="font-medium">{{ session('message') }}</span>
        </div>
        <button onclick="this.parentElement.parentElement.remove()" class="text-green-500 hover:text-green-700">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
</div>
@endif

@if(session('error'))
<div id="flash-message" class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mx-auto max-w-7xl mt-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
        <button onclick="this.parentElement.parentElement.remove()" class="text-red-500 hover:text-red-700">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
</div>
@endif

<!-- ============================================================
     HERO SECTION - Matching Extrarent exactly
     ============================================================ -->
<section class="ex-hero">
    <div class="ex-hero-bg" style="background-image: url('/images/hero/img0001-scaled-1.jpg');"></div>
    <div class="ex-hero-overlay"></div>
    <div class="ex-hero-content container-ex">
        <h1 class="ex-hero-title" style="margin-bottom: 0.2rem;">¡Hola!, ¿Buscas una moto?</h1>
        <h1 class="ex-hero-title mb-12">Estás en el mejor sitio</h1>

        <!-- Search Form -->
        <div class="ex-search-bar max-w-4xl mx-auto">
            <form id="vehicle-search-form" action="{{ route('search.results') }}" method="GET" class="ex-search-grid">
                <!-- Recogida -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="ex-search-label">Recogida</label>
                        <span class="text-xs text-ex-accent font-medium cursor-pointer">Información</span>
                    </div>
                    <div class="w-full">
                        <input type="date" name="pickup_date" class="ex-input w-full" min="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <!-- Devolución -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="ex-search-label">Devolución</label>
                        <span class="text-xs text-ex-accent font-medium cursor-pointer">Información</span>
                    </div>
                    <div class="w-full">
                        <input type="date" name="dropoff_date" class="ex-input w-full" min="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <!-- Vehicle Type -->
                <div>
                    <label class="ex-search-label">Categoría de Vehículo</label>
                    <select name="categories" class="ex-select">
                        <option value="">Todas las categorías</option>
                        @foreach(\App\Models\VehicleCategory::where('is_active', true)->get() as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Search Button -->
                <div>
                    <button type="submit" class="ex-btn ex-btn-primary ex-btn-lg w-full" style="white-space:nowrap;">
                        <svg class="inline h-4 w-4 mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- ============================================================
     CTA BANNER - Phone / WhatsApp / Email
     ============================================================ -->
<section class="py-16 relative overflow-hidden bg-white">
    <!-- Decorative background elements -->
    <div class="absolute inset-0 bg-gradient-to-b from-gray-50/50 to-white pointer-events-none"></div>
    
    <div class="container-ex relative z-10">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight" style="font-family:'Space Grotesk',sans-serif;">
                ¿Necesitas ayuda? <span class="text-ex-accent">Contacta con nosotros</span>
            </h2>
            <p class="mt-4 text-gray-500 max-w-2xl mx-auto text-lg">
                Nuestro equipo está disponible para ayudarte a encontrar el vehículo perfecto para tu estancia en Ibiza.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Phone -->
            <a href="tel:+34971000000" class="group relative flex flex-col items-center justify-center bg-white rounded-3xl p-8 text-center shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] hover:shadow-[0_20px_50px_-10px_rgba(239,68,68,0.15)] hover:-translate-y-2 transition-all duration-500 border border-gray-100 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-red-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative w-20 h-20 rounded-2xl bg-red-50 flex items-center justify-center text-ex-accent group-hover:bg-ex-accent group-hover:text-white group-hover:scale-110 transition-all duration-500 mb-6 shadow-sm">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <h3 class="relative text-sm font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">Llama Ahora</h3>
                <p class="relative text-2xl font-bold text-gray-900 group-hover:text-ex-accent transition-colors duration-300" style="font-family:'Space Grotesk',sans-serif;">+34 971 000 000</p>
            </a>
            
            <!-- Mobile -->
            <a href="tel:+34600000000" class="group relative flex flex-col items-center justify-center bg-white rounded-3xl p-8 text-center shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] hover:shadow-[0_20px_50px_-10px_rgba(59,130,246,0.15)] hover:-translate-y-2 transition-all duration-500 border border-gray-100 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative w-20 h-20 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white group-hover:scale-110 transition-all duration-500 mb-6 shadow-sm">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="relative text-sm font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">Móvil 24h</h3>
                <p class="relative text-2xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-300" style="font-family:'Space Grotesk',sans-serif;">+34 600 000 000</p>
            </a>
            
            <!-- WhatsApp -->
            <a href="https://wa.me/34971000000" target="_blank" class="group relative flex flex-col items-center justify-center bg-white rounded-3xl p-8 text-center shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] hover:shadow-[0_20px_50px_-10px_rgba(34,197,94,0.15)] hover:-translate-y-2 transition-all duration-500 border border-gray-100 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-green-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative w-20 h-20 rounded-2xl bg-green-50 flex items-center justify-center text-green-500 group-hover:bg-green-500 group-hover:text-white group-hover:scale-110 transition-all duration-500 mb-6 shadow-sm">
                    <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.885 9.888-9.885 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c-.001 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </div>
                <h3 class="relative text-sm font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">WhatsApp</h3>
                <p class="relative text-2xl font-bold text-gray-900 group-hover:text-green-500 transition-colors duration-300" style="font-family:'Space Grotesk',sans-serif;">Abrir Chat</p>
            </a>
        </div>
        
        <div class="mt-12 flex justify-center">
            <div class="inline-flex items-center gap-3 px-6 py-3 rounded-full bg-gray-50 border border-gray-200 shadow-sm text-sm font-medium text-gray-600">
                <span class="flex h-3 w-3 relative">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <span><strong>100% Disponibilidad</strong> &bull; Horario: 8:00h - 22:00h (Fuera de horario vía WhatsApp)</span>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     VEHICLE CATEGORIES - From database
     ============================================================ -->
<section class="ex-section">
    <div class="container-ex">
        <div class="text-center mb-10">
            <h2 class="ex-section-title">Encuentra el vehículo adecuado para cada ocasión</h2>
            <hr class="ex-accent-line">
        </div>

        @php
            $featuredVehicles = \App\Models\Vehicle::with('images')
                ->where('is_active', true)
                ->where('show_on_homepage', true)
                ->orderBy('daily_rate')
                ->take(3)
                ->get();
        @endphp

        @if($featuredVehicles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($featuredVehicles as $vehicle)
                <div class="ex-card group cursor-pointer transition-all duration-300 hover:shadow-xl hover:-translate-y-1 bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div style="height:240px;overflow:hidden;" class="relative bg-gray-50 flex items-center justify-center p-4">
                        @if($vehicle->images->count() > 0)
                            <img src="{{ $vehicle->images->first()->url }}" alt="{{ $vehicle->name }}" class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-110 drop-shadow-md" onerror="this.src='/images/hero/img0002-scaled-1.jpg'">
                        @else
                            <img src="/images/hero/img0002-scaled-1.jpg" alt="{{ $vehicle->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @endif
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-gray-900 shadow-sm border border-gray-100">
                            {{ $vehicle->engine ?? '125cc' }}
                        </div>
                    </div>
                    <div class="ex-card-body p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ $vehicle->brand }}</span>
                            <span class="flex items-center text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                Disponible
                            </span>
                        </div>
                        <h3 class="ex-card-title text-xl font-bold mb-2 text-gray-900">{{ $vehicle->name }}</h3>
                        <p class="text-sm text-gray-500 mb-6 line-clamp-2">{{ $vehicle->description ?? 'El scooter perfecto para moverse por Ibiza.' }}</p>
                        
                        <div class="flex items-center justify-between mt-auto">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-semibold tracking-wider">Desde</p>
                                <p class="text-ex-accent font-bold text-2xl" style="font-family:'Space Grotesk',sans-serif;">{{ number_format($vehicle->daily_rate, 0) }}€<span class="text-sm text-gray-500 font-normal">/día</span></p>
                            </div>
                            <a href="/search?type={{ $vehicle->slug }}" class="ex-btn ex-btn-primary px-6 py-2 rounded-xl group-hover:shadow-lg group-hover:shadow-red-500/20 transition-all">
                                Reservar
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Fallback if no vehicles are seeded yet -->
            <div class="text-center py-10 bg-gray-50 rounded-2xl border border-gray-100">
                <p class="text-gray-500">Nuestra flota se está actualizando. Por favor, vuelve pronto.</p>
            </div>
        @endif
    </div>
</section>

<!-- ============================================================
     SECTION 01 - Sobre Nosotros
     ============================================================ -->
<section class="py-20 md:py-28 relative overflow-hidden bg-white">
    <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-bl from-red-50 to-transparent rounded-full -translate-y-1/2 translate-x-1/3 opacity-60 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-gradient-to-tr from-gray-100 to-transparent rounded-full translate-y-1/3 -translate-x-1/4 pointer-events-none"></div>

    <div class="container-ex relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Text content -->
            <div class="order-2 lg:order-1">
                <div class="inline-flex items-center gap-3 mb-6">
                    <span class="text-5xl font-extrabold bg-gradient-to-br from-red-500 to-red-700 bg-clip-text text-transparent" style="font-family:'Space Grotesk',sans-serif;">01</span>
                    <div class="w-12 h-px bg-gradient-to-r from-red-400 to-transparent"></div>
                </div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-4" style="font-family:'Space Grotesk',sans-serif;">
                    Alquilar una moto es <span class="bg-gradient-to-r from-red-600 to-red-500 bg-clip-text text-transparent">más barato</span> de lo que piensas
                </h2>
                <div class="w-16 h-1 bg-gradient-to-r from-red-500 to-red-300 rounded-full mb-6"></div>

                <p class="text-gray-600 leading-relaxed text-lg mb-5">
                    En <strong class="text-gray-900">Extrarent Ibiza</strong> nos complace poner a tu disposición nuestros servicios profesionales de alquiler de vehículos.
                    Nuestra excelente localización, justo en el centro de la Isla, nos diferencia: podrás alquilar una moto en el puerto de Ibiza
                    sin grandes desplazamientos, de manera cómoda y segura.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-5 py-3 border border-gray-100">
                        <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-red-500 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Motos 125cc</p>
                            <p class="text-xs text-gray-500">Piaggio · SYM · Vespa</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-5 py-3 border border-gray-100">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Coches</p>
                            <p class="text-xs text-gray-500">Vía nuestra asociada Class</p>
                        </div>
                    </div>
                </div>

                <a href="/nosotros" class="group inline-flex items-center gap-2 ex-btn ex-btn-primary px-8 py-3 rounded-xl text-base font-semibold shadow-lg shadow-red-500/20 hover:shadow-red-500/30 transition-all duration-300">
                    Conócenos
                    <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <!-- Image -->
            <div class="order-1 lg:order-2 group">
                <div class="relative">
                    <div class="absolute -inset-4 bg-gradient-to-tr from-red-100 via-transparent to-blue-50 rounded-3xl opacity-60 group-hover:opacity-80 transition-opacity duration-500"></div>
                    <img src="/images/hero/nueva_fachada.jpg" alt="Extrarent Ibiza - Oficina en el Puerto" class="relative w-full rounded-2xl shadow-2xl shadow-gray-900/10 group-hover:shadow-gray-900/20 transition-all duration-500 group-hover:scale-[1.02]"
                         onerror="this.style.display='none'">
                    <div class="absolute -bottom-4 -right-4 bg-white rounded-2xl px-6 py-4 shadow-xl border border-gray-100 hidden md:flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Puerto de Ibiza</p>
                            <p class="text-xs text-gray-500">Centro de la isla</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     SECTION 02 - Confía en Nosotros / Features
     ============================================================ -->
<section class="py-20 md:py-28 relative overflow-hidden" style="background: linear-gradient(135deg, #f8f9fb 0%, #f0f1f5 100%);">
    <div class="absolute top-10 left-10 w-72 h-72 bg-red-100 rounded-full mix-blend-multiply filter blur-3xl opacity-20 pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-72 h-72 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-20 pointer-events-none"></div>

    <div class="container-ex relative z-10">
        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-3 mb-4">
                <div class="w-12 h-px bg-gradient-to-r from-transparent to-red-400"></div>
                <span class="text-5xl font-extrabold bg-gradient-to-br from-red-500 to-red-700 bg-clip-text text-transparent" style="font-family:'Space Grotesk',sans-serif;">02</span>
                <div class="w-12 h-px bg-gradient-to-l from-transparent to-red-400"></div>
            </div>
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight" style="font-family:'Space Grotesk',sans-serif;">
                ¿Por qué <span class="bg-gradient-to-r from-red-600 to-red-500 bg-clip-text text-transparent">elegirnos</span>?
            </h2>
            <p class="mt-4 text-gray-500 max-w-xl mx-auto text-lg">Las mejores ofertas y servicios de alquiler de motos en Ibiza</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Feature 1 -->
            <div class="group relative bg-white/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-gray-100/80 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_50px_-12px_rgba(234,0,30,0.12)] hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-red-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center text-red-500 group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-red-100 transition-all duration-500 mb-5">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-900 text-lg mb-3" style="font-family:'Space Grotesk',sans-serif;">100% Disponibilidad</h4>
                    <p class="text-gray-500 text-sm leading-relaxed">Horario de 8:00h a 22:00h.<br>Fuera de horario por WhatsApp</p>
                </div>
            </div>
            <!-- Feature 2 -->
            <div class="group relative bg-white/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-gray-100/80 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_50px_-12px_rgba(0,86,222,0.12)] hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-blue-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center text-blue-500 group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-blue-100 transition-all duration-500 mb-5">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-900 text-lg mb-3" style="font-family:'Space Grotesk',sans-serif;">Mejores Precios</h4>
                    <p class="text-gray-500 text-sm leading-relaxed">Piaggio, SYM, Vespa<br>125cc conducibles con carnet B</p>
                </div>
            </div>
            <!-- Feature 3 -->
            <div class="group relative bg-white/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-gray-100/80 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_50px_-12px_rgba(16,185,129,0.12)] hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-green-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center text-green-500 group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-green-100 transition-all duration-500 mb-5">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-900 text-lg mb-3" style="font-family:'Space Grotesk',sans-serif;">Pago 100% Seguro</h4>
                    <p class="text-gray-500 text-sm leading-relaxed">Transacciones protegidas<br>Todos los métodos de pago</p>
                </div>
            </div>
            <!-- Feature 4 -->
            <div class="group relative bg-white/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-gray-100/80 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_50px_-12px_rgba(168,85,247,0.12)] hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-purple-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100 flex items-center justify-center text-purple-500 group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-purple-100 transition-all duration-500 mb-5">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-900 text-lg mb-3" style="font-family:'Space Grotesk',sans-serif;">Soporte Multilingüe</h4>
                    <p class="text-gray-500 text-sm leading-relaxed">Español, Inglés, Alemán, Francés<br>Te asesoramos en tu visita</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     SECTION 03 - Consigna / Servicios Extra
     ============================================================ -->
<section class="py-20 md:py-28 bg-white relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-red-200 to-transparent"></div>

    <div class="container-ex relative z-10">
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-3 mb-4">
                <div class="w-12 h-px bg-gradient-to-r from-transparent to-red-400"></div>
                <span class="text-5xl font-extrabold bg-gradient-to-br from-red-500 to-red-700 bg-clip-text text-transparent" style="font-family:'Space Grotesk',sans-serif;">03</span>
                <div class="w-12 h-px bg-gradient-to-l from-transparent to-red-400"></div>
            </div>
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight" style="font-family:'Space Grotesk',sans-serif;">
                Servicios <span class="bg-gradient-to-r from-blue-600 to-blue-500 bg-clip-text text-transparent">adicionales</span>
            </h2>
        </div>

        <div class="bg-gradient-to-br from-gray-50 to-white rounded-3xl p-8 md:p-12 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.06)] border border-gray-100 group hover:shadow-[0_20px_60px_-10px_rgba(0,0,0,0.1)] transition-all duration-500">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-full mb-6">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        Consigna de equipajes
                    </div>
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4" style="font-family:'Space Grotesk',sans-serif;">
                        Custodia de maletas y equipajes en Ibiza
                    </h3>
                    <p class="text-gray-600 leading-relaxed text-lg mb-8">
                        Además del alquiler de motos te ofrecemos los servicios de <strong class="text-gray-900">Consigna Ibiza Puerto</strong>, donde
                        custodiamos tus pertenencias con total seguridad hasta que puedas hacerte cargo de ellas.
                        Llevamos años dedicándonos a este sector y nuestros clientes repiten siempre.
                    </p>
                    <a href="#" class="group/btn inline-flex items-center gap-2 ex-btn ex-btn-secondary px-8 py-3 rounded-xl text-base font-semibold shadow-lg shadow-blue-500/20 hover:shadow-blue-500/30 transition-all duration-300">
                        Visita Consigna Ibiza
                        <svg class="w-4 h-4 transition-transform duration-300 group-hover/btn:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
                <div class="relative">
                    <div class="absolute -inset-3 bg-gradient-to-tr from-blue-100 via-transparent to-red-50 rounded-3xl opacity-50 group-hover:opacity-70 transition-opacity duration-500"></div>
                    <img src="/images/hero/img0000-scaled-1.jpg" alt="Consigna Ibiza" class="relative w-full rounded-2xl shadow-lg group-hover:scale-[1.02] transition-transform duration-500"
                         onerror="this.style.display='none'">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     OFFICE LOCATION
     ============================================================ -->
<section class="py-20 md:py-28 relative overflow-hidden" style="background: linear-gradient(135deg, #f8f9fb 0%, #f0f1f5 100%);">
    <div class="container-ex">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight" style="font-family:'Space Grotesk',sans-serif;">
                Nuestra oficina en el <span class="bg-gradient-to-r from-red-600 to-red-500 bg-clip-text text-transparent">Puerto</span>
            </h2>
            <p class="mt-4 text-gray-500 max-w-lg mx-auto text-lg">Estaremos encantados de recibirte en nuestras instalaciones</p>
        </div>

        <div class="relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-red-200 via-blue-200 to-red-200 rounded-3xl opacity-30 group-hover:opacity-50 blur-sm transition-opacity duration-500"></div>
            <div class="relative rounded-2xl overflow-hidden shadow-2xl shadow-gray-900/10" style="height:400px;">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3080.!2d1.424!3d38.892!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12914170d0deeddb%3A0x8d28d !2dPuerto+de+Ibiza!5e0!3m2!1ses!2ses!4v1234567890"
                        width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
            <!-- Info card floating on map -->
            <div class="absolute bottom-6 left-6 bg-white/95 backdrop-blur-md rounded-2xl px-6 py-5 shadow-xl border border-gray-100 hidden md:flex items-center gap-4 z-10">
                <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">Avda. Santa Eulària des Riu, 25</p>
                    <p class="text-xs text-gray-500">Puerto de Ibiza · 07800</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     STATS COUNTERS
     ============================================================ -->
<section class="py-20 md:py-24 relative overflow-hidden" style="background: linear-gradient(135deg, #161829 0%, #1e2040 50%, #161829 100%);">
    <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.4&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-red-500/30 to-transparent"></div>

    <div class="container-ex relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="group">
                <div class="text-5xl md:text-6xl font-extrabold text-white mb-2 group-hover:scale-110 transition-transform duration-300" style="font-family:'Space Grotesk',sans-serif;">
                    {{ $vehicleCount ?? 50 }}<span class="text-red-400">+</span>
                </div>
                <div class="text-gray-400 text-sm uppercase tracking-wider font-medium">Vehículos</div>
            </div>
            <div class="group">
                <div class="text-5xl md:text-6xl font-extrabold text-white mb-2 group-hover:scale-110 transition-transform duration-300" style="font-family:'Space Grotesk',sans-serif;">
                    {{ $customerCount ?? '2.5K' }}<span class="text-red-400">+</span>
                </div>
                <div class="text-gray-400 text-sm uppercase tracking-wider font-medium">Clientes Felices</div>
            </div>
            <div class="group">
                <div class="text-5xl md:text-6xl font-extrabold text-white mb-2 group-hover:scale-110 transition-transform duration-300" style="font-family:'Space Grotesk',sans-serif;">
                    {{ $bookingCount ?? '5K' }}<span class="text-red-400">+</span>
                </div>
                <div class="text-gray-400 text-sm uppercase tracking-wider font-medium">Reservas</div>
            </div>
            <div class="group">
                <div class="text-5xl md:text-6xl font-extrabold text-white mb-2 group-hover:scale-110 transition-transform duration-300" style="font-family:'Space Grotesk',sans-serif;">
                    100<span class="text-red-400">%</span>
                </div>
                <div class="text-gray-400 text-sm uppercase tracking-wider font-medium">Disponibilidad</div>
            </div>
        </div>
    </div>

    <div class="absolute bottom-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-red-500/30 to-transparent"></div>
</section>


