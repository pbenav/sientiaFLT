@extends('layouts.app')

@push('scripts')
<script>
    setTimeout(() => {
        const flash = document.getElementById('flash-message');
        if (flash) flash.remove();
    }, 5000);
</script>
@endpush

@section('content')

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
<section class="bg-ex-light py-10">
    <div class="container-ex">
        <div class="text-center mb-8">
            <h2 class="ex-section-title" style="font-size:2rem;">¿Necesitas ayuda? Contacta con nosotros</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
            <!-- Phone -->
            <a href="tel:+34971000000" class="flex flex-col items-center justify-center bg-white rounded-2xl p-6 text-center shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.1)] hover:-translate-y-1 transition-all duration-300 border border-gray-100 group">
                <div class="w-16 h-16 rounded-2xl bg-red-50 flex items-center justify-center text-ex-accent group-hover:bg-ex-accent group-hover:text-white transition-colors duration-300 mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1 whitespace-nowrap">Llama Ahora</h3>
                <p class="text-xl font-bold text-gray-800 whitespace-nowrap" style="font-family:'Space Grotesk',sans-serif;">+34 971 000 000</p>
            </a>
            <!-- Mobile -->
            <a href="tel:+34600000000" class="flex flex-col items-center justify-center bg-white rounded-2xl p-6 text-center shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.1)] hover:-translate-y-1 transition-all duration-300 border border-gray-100 group">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center text-ex-blue group-hover:bg-ex-blue group-hover:text-white transition-colors duration-300 mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1 whitespace-nowrap">Móvil 24h</h3>
                <p class="text-xl font-bold text-gray-800 whitespace-nowrap" style="font-family:'Space Grotesk',sans-serif;">+34 600 000 000</p>
            </a>
            <!-- WhatsApp -->
            <a href="https://wa.me/34971000000" target="_blank" class="flex flex-col items-center justify-center bg-white rounded-2xl p-6 text-center shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.1)] hover:-translate-y-1 transition-all duration-300 border border-gray-100 group">
                <div class="w-16 h-16 rounded-2xl bg-green-50 flex items-center justify-center text-green-500 group-hover:bg-green-500 group-hover:text-white transition-colors duration-300 mb-4">
                    <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.885 9.888-9.885 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c-.001 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1 whitespace-nowrap">WhatsApp</h3>
                <p class="text-xl font-bold text-gray-800 whitespace-nowrap" style="font-family:'Space Grotesk',sans-serif;">Abrir Chat</p>
            </a>
        </div>
        <p class="text-center text-sm text-ex-text mt-6">
            <strong>100% Disponibilidad</strong> - Alquiler motos en Ibiza :: Extra Rent. Horario comercial: de 8:00h. a 22:00h. Fuera de Horario: Usa WhatsApp
        </p>
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

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @php
                $categories = [
                    ['name' => 'Scooter 125cc', 'slug' => 'scooter', 'image' => '/images/vehicles/sym-symphony-125.jpg', 'from' => '60'],
                    ['name' => 'Compactos', 'slug' => 'compact', 'image' => '/images/vehicles/fiat-500.jpg', 'from' => '25'],
                    ['name' => 'SUV', 'slug' => 'suv', 'image' => '/images/vehicles/suv.jpg', 'from' => '45'],
                    ['name' => 'Furgonetas', 'slug' => 'van', 'image' => '/images/vehicles/van.jpg', 'from' => '55'],
                ];
            @endphp

            @foreach($categories as $cat)
            <div class="ex-card">
                <div style="height:180px;overflow:hidden;">
                    <img src="{{ $cat['image'] }}" alt="{{ $cat['name'] }}" class="w-full h-full object-cover"
                         onerror="this.src='/images/hero/img0002-scaled-1.jpg'">
                </div>
                <div class="ex-card-body">
                    <h3 class="ex-card-title">{{ $cat['name'] }}</h3>
                    <p class="ex-card-text">Desde <span class="text-ex-accent" style="font-weight:700;font-size:1.1rem;">{{ $cat['from'] }}€</span>/día</p>
                    <a href="/search?type={{ $cat['slug'] }}" class="ex-btn ex-btn-primary ex-btn-sm" style="width:100%;">Ver Vehículos</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ============================================================
     SECTION 01 - Sobre Nosotros
     ============================================================ -->
<section class="ex-section" style="background:#fff;">
    <div class="container-ex">
        <div class="text-center mb-10">
            <span class="text-ex-accent" style="font-size:1.5rem;font-weight:700;font-family:'Space Grotesk',sans-serif;">01</span>
            <h2 class="ex-section-title" style="margin-top:5px;">Qué Ofrecemos</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            <div>
                <h3 style="font-family:'Space Grotesk',sans-serif;font-size:1.5rem;font-weight:600;color:#161829;margin-bottom:15px;">
                    Alquilar una moto es más barato de lo que piensas
                </h3>
                <p style="color:#4C586C;line-height:1.8;margin-bottom:15px;">
                    <strong>EMPRESA DE ALQUILER DE VEHÍCULOS</strong><br>
                    En Extrarent Ibiza nos complace poner a tu disposición nuestros servicios profesionales de alquiler de vehículos.
                    Varios puntos importantes nos diferencian de la competencia, uno de ellos es nuestra excelente localización,
                    justo en el centro de la Isla. Con Extrarent Ibiza podrás alquilar una moto en el puerto de Ibiza,
                    sin realizar grandes desplazamientos. De una manera cómoda y segura, en una de las zonas más exclusivas de Ibiza.
                </p>
                <p style="color:#4C586C;line-height:1.8;">
                    <strong>MOTOS,</strong> 125 !! PIAGGIO/SYM/ VESPA…<br>
                    <strong>COCHES,</strong> a través de nuestra asociada Class.
                </p>
                <div style="margin-top:25px;">
                    <a href="/nosotros" class="ex-btn ex-btn-primary">Conócenos</a>
                </div>
            </div>
            <div>
                <img src="/images/hero/nueva_fachada.jpg" alt="Extrarent Ibiza" class="w-full rounded-lg"
                     onerror="this.style.display='none'">
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     SECTION 02 - Confía en Nosotros / Features
     ============================================================ -->
<section class="ex-section" style="background:#F4F5F6;">
    <div class="container-ex">
        <div class="text-center mb-10">
            <span class="text-ex-accent" style="font-size:1.5rem;font-weight:700;font-family:'Space Grotesk',sans-serif;">02</span>
            <h2 class="ex-section-title" style="margin-top:5px;">Confía en Nosotros</h2>
            <p style="color:#4C586C;font-size:1.05rem;margin-top:10px;">Características de las mejores ofertas y servicios de alquiler de coches y motos</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="ex-icon-box bg-white rounded-lg">
                <div class="ex-icon-box-icon">
                    <svg class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h4 class="ex-icon-box-title">100% Disponibilidad</h4>
                <p class="ex-icon-box-desc">Extra Rent<br>Horario de 8:00h. a 22:00h.<br>Fuera de Horario por WhatsApp</p>
            </div>

            <div class="ex-icon-box bg-white rounded-lg">
                <div class="ex-icon-box-icon">
                    <svg class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h4 class="ex-icon-box-title">Alquiler de Motos</h4>
                <p class="ex-icon-box-desc">Las mejores marcas<br>Piaggio, SYM, Vespa<br>125cc sin carnet B</p>
            </div>

            <div class="ex-icon-box bg-white rounded-lg">
                <div class="ex-icon-box-icon">
                    <svg class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h4 class="ex-icon-box-title">Garantía de pago seguro</h4>
                <p class="ex-icon-box-desc">Transacciones protegidas<br>Todos los métodos de pago<br>Reserva segura</p>
            </div>

            <div class="ex-icon-box bg-white rounded-lg">
                <div class="ex-icon-box-icon">
                    <svg class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                    </svg>
                </div>
                <h4 class="ex-icon-box-title">Soporte multilingüe</h4>
                <p class="ex-icon-box-desc">Español, Inglés,<br>Alemán, Francés<br>Te asesoramos en tu visita</p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     SECTION 03 - Consigna / Servicios Extra
     ============================================================ -->
<section class="ex-section">
    <div class="container-ex">
        <div class="text-center mb-10">
            <span class="text-ex-accent" style="font-size:1.5rem;font-weight:700;font-family:'Space Grotesk',sans-serif;">03</span>
            <h2 class="ex-section-title" style="margin-top:5px;">Qué Ofrecemos</h2>
        </div>

        <div class="bg-white rounded-lg p-8 shadow-sm border border-ex-light">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <h3 style="font-family:'Space Grotesk',sans-serif;font-size:1.5rem;font-weight:600;color:#161829;margin-bottom:15px;">
                        Servicio de Consigna de Maletas y Equipajes en la Isla de Ibiza
                    </h3>
                    <p style="color:#4C586C;line-height:1.8;margin-bottom:20px;">
                        Además del alquiler de motos te ofrecemos los servicios de Consigna Ibiza Puerto, donde
                        custodiamos tus pertenencias con total seguridad hasta que puedas hacerte cargo de ellas.
                        Llevamos años dedicándonos al sector de la consigna en Ibiza y son muchos los clientes que,
                        tras contratar nuestros servicios, no dudan en repetir.
                    </p>
                    <a href="#" class="ex-btn ex-btn-secondary">Visita Consigna Ibiza</a>
                </div>
                <div>
                    <img src="/images/hero/img0000-scaled-1.jpg" alt="Consigna Ibiza" class="w-full rounded-lg"
                         onerror="this.style.display='none'">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     OFFICE LOCATION
     ============================================================ -->
<section class="ex-section" style="background:#F4F5F6;">
    <div class="container-ex text-center">
        <h2 class="ex-section-title">Nuestra Oficina en el Puerto</h2>
        <p style="color:#4C586C;margin-bottom:30px;">Estaremos encantados de recibirte en nuestras instalaciones !</p>
        <div style="border-radius:8px;overflow:hidden;height:350px;background:#ddd;">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3080.!2d1.424!3d38.892!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12914170d0deeddb%3A0x8d28d !2dPuerto+de+Ibiza!5e0!3m2!1ses!2ses!4v1234567890"
                    width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</section>

<!-- ============================================================
     STATS COUNTERS
     ============================================================ -->
<section class="ex-section" style="background:#161829;color:#fff;">
    <div class="container-ex">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="ex-counter-number" style="color:#fff;">{{ $vehicleCount ?? 50 }}+</div>
                <div class="ex-counter-title" style="color:#C9C6C6;">Vehículos</div>
            </div>
            <div>
                <div class="ex-counter-number" style="color:#fff;">{{ $customerCount ?? 2500 }}+</div>
                <div class="ex-counter-title" style="color:#C9C6C6;">Clientes Felices</div>
            </div>
            <div>
                <div class="ex-counter-number" style="color:#fff;">{{ $bookingCount ?? 5000 }}+</div>
                <div class="ex-counter-title" style="color:#C9C6C6;">Reservas</div>
            </div>
            <div>
                <div class="ex-counter-number" style="color:#fff;">100%</div>
                <div class="ex-counter-title" style="color:#C9C6C6;">Disponibilidad</div>
            </div>
        </div>
    </div>
</section>

@endsection
