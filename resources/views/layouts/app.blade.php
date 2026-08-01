<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - {{ $pageTitle ?? 'Vehicle Fleet Management' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Lato:wght@300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<body class="antialiased">

    <!-- Top Bar -->
    <div class="ex-header-top">
        <div class="container-ex">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-6">
                    <a href="mailto:info@extrarent.com">
                        <svg class="inline h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        info@extrarent.com
                    </a>
                    <span>
                        <svg class="inline h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Ibiza - Puerto
                    </span>
                </div>
                <div class="flex items-center space-x-6">
                    <span>
                        <svg class="inline h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Lun - Dom: 08:00 - 22:00
                    </span>
                    <div class="flex items-center space-x-3">
                        <a href="https://facebook.com" target="_blank" class="text-ex-gray hover:text-white transition-colors">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://instagram.com" target="_blank" class="text-ex-gray hover:text-white transition-colors">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <a href="mailto:info@extrarent.com" class="text-ex-gray hover:text-white transition-colors">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="ex-header">
        <div class="ex-header-main">
            <div class="container-ex">
                <nav class="ex-nav">
                    <!-- Logo -->
                    <a href="/" class="flex items-center flex-shrink-0">
                        <img src="/images/logos/cropped-logo-web-extrarent.jpg" alt="Extrarent" class="h-12 w-auto">
                    </a>

                    <!-- Navigation Links - Dynamic from Menus -->
                    <ul class="ex-nav-links">
                        @foreach($menuItems ?? collect() as $item)
                            @if($item->type === 'separator')
                                <li class="ex-nav-separator"></li>
                            @else
                                @php
                                    $url = $item->type === 'page' && $item->page
                                        ? '/pages/' . $item->page->slug
                                        : ($item->url ?? '#');
                                    $isActive = request()->is(trim($url, '/'));
                                @endphp
                                <li>
                                    <a href="{{ $url }}" class="{{ $isActive ? 'active' : '' }}" target="{{ $item->target ?? '_self' }}">
                                        {{ $item->title }}
                                    </a>
                                    @if($item->children && $item->children->count() > 0)
                                        <ul class="ex-nav-dropdown">
                                            @foreach($item->children as $child)
                                                @php
                                                    $childUrl = $child->type === 'page' && $child->page
                                                        ? '/pages/' . $child->page->slug
                                                        : ($child->url ?? '#');
                                                @endphp
                                                <li>
                                                    <a href="{{ $childUrl }}" class="{{ request()->is(trim($childUrl, '/')) ? 'active' : '' }}">
                                                        {{ $child->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endif
                        @endforeach
                        <!-- Fallback: show published menu pages if no menu items configured -->
                        @if(($menuItems ?? collect())->isEmpty() && ($menuPages ?? collect())->count() > 0)
                            @foreach($menuPages as $page)
                                <li>
                                    <a href="/pages/{{ $page->slug }}" class="{{ request()->is('pages/' . $page->slug) ? 'active' : '' }}">
                                        {{ $page->title }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>

                    <!-- Actions -->
                    <div class="ex-header-actions">
                        <a href="tel:+34971000000" class="ex-phone">
                            <svg class="inline h-4 w-4 mr-1 text-ex-accent" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
                            </svg>
                            +34 971 000 000
                        </a>
                        @auth
                            <span class="text-sm text-ex-text">Hola, {{ auth()->user()->name }}</span>
                            <a href="/admin" class="ex-btn ex-btn-primary ex-btn-sm">Admin</a>
                            <form method="POST" action="/admin/logout" class="inline">
                                @csrf
                                <button type="submit" class="ex-btn ex-btn-outline ex-btn-sm">Logout</button>
                            </form>
                        @else
                            <a href="/admin/login" class="ex-btn ex-btn-primary ex-btn-sm">Sign In</a>
                        @endauth
                        <button class="ex-mobile-toggle" id="mobile-menu-button" aria-label="Menu">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="ex-mobile-menu" id="mobile-menu">
            @foreach($menuItems ?? collect() as $item)
                @if($item->type !== 'separator')
                    @php
                        $url = $item->type === 'page' && $item->page
                            ? '/pages/' . $item->page->slug
                            : ($item->url ?? '#');
                    @endphp
                    <a href="{{ $url }}" class="{{ request()->is(trim($url, '/')) ? 'bg-ex-light' : '' }}">
                        {{ $item->title }}
                    </a>
                    @if($item->children && $item->children->count() > 0)
                        @foreach($item->children as $child)
                            @php
                                $childUrl = $child->type === 'page' && $child->page
                                    ? '/pages/' . $child->page->slug
                                    : ($child->url ?? '#');
                            @endphp
                            <a href="{{ $childUrl }}" style="padding-left: 20px;" class="{{ request()->is(trim($childUrl, '/')) ? 'bg-ex-light' : '' }}">
                                └ {{ $child->title }}
                            </a>
                        @endforeach
                    @endif
                @endif
            @endforeach
            @if(($menuItems ?? collect())->isEmpty() && ($menuPages ?? collect())->count() > 0)
                @foreach($menuPages as $page)
                    <a href="/pages/{{ $page->slug }}" class="{{ request()->is('pages/' . $page->slug) ? 'bg-ex-light' : '' }}">
                        {{ $page->title }}
                    </a>
                @endforeach
            @endif
            @auth
                <a href="/admin" class="ex-btn ex-btn-primary ex-btn-sm" style="margin-top:10px;text-align:center;">Admin Panel</a>
                <form method="POST" action="/admin/logout">
                    @csrf
                    <button type="submit" class="ex-btn ex-btn-outline ex-btn-sm" style="margin-top:10px;width:100%;">Logout</button>
                </form>
            @else
                <a href="/admin/login" class="ex-btn ex-btn-primary ex-btn-sm" style="margin-top:10px;text-align:center;">Sign In</a>
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="ex-footer">
        <div class="container-ex">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <img src="/images/logos/cropped-logo-web-extrarent.jpg" alt="Extrarent" class="h-10 w-auto mb-4" style="filter: brightness(0) invert(1);">
                    <p class="text-sm mb-4">Alquiler de motos y coches en Ibiza. Varios puntos importantes nos diferencian de la competencia, uno de ellos es nuestra excelente localización, justo en el centro de la Isla.</p>
                    <div class="flex space-x-4">
                        <a href="https://facebook.com" target="_blank" class="text-ex-gray hover:text-white transition-colors">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://instagram.com" target="_blank" class="text-ex-gray hover:text-white transition-colors">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <a href="mailto:info@extrarent.com" class="text-ex-gray hover:text-white transition-colors">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links - Dynamic from Footer Menu -->
                <div>
                    <h4>Enlaces Rápidos</h4>
                    <ul class="ex-footer-links">
                        @forelse($footerItems ?? collect() as $item)
                            @if($item->type !== 'separator')
                                @php
                                    $url = $item->type === 'page' && $item->page
                                        ? '/pages/' . $item->page->slug
                                        : ($item->url ?? '#');
                                @endphp
                                <li><a href="{{ $url }}">{{ $item->title }}</a></li>
                            @endif
                        @empty
                            <!-- Fallback: show published pages -->
                            <li><a href="/">Inicio</a></li>
                            <li><a href="/search">Nuestros Vehículos</a></li>
                            @foreach($menuPages ?? collect() as $page)
                                @if($page->slug !== 'inicio')
                                <li><a href="/pages/{{ $page->slug }}">{{ $page->title }}</a></li>
                                @endif
                            @endforeach
                        @endforelse
                    </ul>
                </div>

                <!-- Vehicle Types -->
                <div>
                    <h4>Vehículos</h4>
                    <ul class="ex-footer-links">
                        <li><a href="/search?type=scooter">Motos 125cc</a></li>
                        <li><a href="/search?type=compact">Coches Compactos</a></li>
                        <li><a href="/search?type=suv">SUVs</a></li>
                        <li><a href="/search?type=sedan">Sedanes</a></li>
                        <li><a href="/search?type=van">Furgonetas</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4>Contacto</h4>
                    <ul class="ex-footer-links">
                        <li>
                            <svg class="inline h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Puerto de Ibiza, Islas Baleares
                        </li>
                        <li>
                            <svg class="inline h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            +34 971 000 000
                        </li>
                        <li>
                            <svg class="inline h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            info@extrarent.com
                        </li>
                        <li>Horario: 08:00 - 22:00</li>
                    </ul>
                </div>
            </div>

            <div class="ex-footer-bottom">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p>&copy; {{ date('Y') }} Extrarent Ibiza. Todos los derechos reservados.</p>
                    <div class="flex space-x-6 mt-4 md:mt-0 text-sm">
                        @if($legalPages['aviso-legal'] ?? null)
                            <a href="/pages/{{ $legalPages['aviso-legal']->slug }}" class="hover:text-white transition-colors">Aviso legal</a>
                        @else
                            <a href="/aviso-legal" class="hover:text-white transition-colors">Aviso legal</a>
                        @endif
                        @if($legalPages['privacidad'] ?? null)
                            <a href="/pages/{{ $legalPages['privacidad']->slug }}" class="hover:text-white transition-colors">Política de privacidad</a>
                        @else
                            <a href="/privacidad" class="hover:text-white transition-colors">Política de privacidad</a>
                        @endif
                        @if($legalPages['cookies'] ?? null)
                            <a href="/pages/{{ $legalPages['cookies']->slug }}" class="hover:text-white transition-colors">Cookies</a>
                        @else
                            <a href="/cookies" class="hover:text-white transition-colors">Cookies</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/34971000000" target="_blank" class="ex-whatsapp" title="WhatsApp">
        <svg class="h-7 w-7 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.885 9.888-9.885 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c-.001 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>

    <script>
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            document.getElementById('mobile-menu')?.classList.toggle('active');
        });
    </script>

    @stack('scripts')
</body>
</html>
