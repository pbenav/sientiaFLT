@extends('layouts.app')

@section('content')
<section class="ex-section flex items-center justify-center" style="background: #F4F5F6; min-height: calc(100vh - 80px);">
    <div class="container-ex py-10">
        <div class="max-w-2xl mx-auto">
            
            <div class="ex-card text-center overflow-hidden">
                <!-- Barra superior verde de éxito -->
                <div class="h-3 bg-green-500 w-full"></div>
                
                <div class="ex-card-body p-10 md:p-14">
                    <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-50 mb-8">
                        <svg class="h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    
                    <h1 class="text-4xl font-bold text-gray-900 mb-4" style="font-family: 'Space Grotesk', sans-serif;">{{ __('¡Reserva Confirmada!') }}</h1>
                    <p class="text-lg text-gray-600 mb-10">{{ __('Gracias por confiar en nosotros') }}, <strong>{{ $booking->customer->first_name }}</strong>.</p>
                    
                    <!-- Ticket de Localizador -->
                    <div class="relative bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl p-8 mb-10">
                        <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-2">{{ __('Tu Localizador') }}</p>
                        <p class="text-4xl font-bold text-ex-primary tracking-wider" style="font-family: 'Space Grotesk', sans-serif;">{{ $booking->booking_number }}</p>
                        
                        <!-- Puntas recortadas estilo ticket -->
                        <div class="absolute -left-4 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-white rounded-full border-r-2 border-gray-300" style="clip-path: inset(0 0 0 50%);"></div>
                        <div class="absolute -right-4 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-white rounded-full border-l-2 border-gray-300" style="clip-path: inset(0 50% 0 0);"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-left bg-white border border-gray-100 rounded-lg p-6 mb-10 shadow-sm">
                        <div class="col-span-2 pb-4 border-b border-gray-100">
                            <span class="block text-xs text-gray-400 uppercase tracking-wide font-bold mb-1">{{ __('Vehículo') }}</span>
                            <span class="font-medium text-gray-900 text-lg">{{ $booking->vehicle->name }}</span>
                        </div>
                        <div class="pt-2">
                            <span class="block text-xs text-gray-400 uppercase tracking-wide font-bold mb-1">{{ __('Recogida') }}</span>
                            <span class="font-bold text-gray-900">{{ $booking->start_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="pt-2">
                            <span class="block text-xs text-gray-400 uppercase tracking-wide font-bold mb-1">{{ __('Devolución') }}</span>
                            <span class="font-bold text-gray-900">{{ $booking->end_date->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <p class="text-gray-500 mb-10 text-sm">
                        {{ __('Te hemos enviado un correo electrónico con todos los detalles de tu reserva a') }} <br>
                        <strong class="text-gray-800">{{ $booking->customer->email }}</strong>
                    </p>
                    
                    <a href="/" class="ex-btn border-2 border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white transition-colors duration-300 px-8 py-3">
                        {{ __('Volver al Inicio') }}
                    </a>
                </div>
            </div>
            
        </div>
    </div>
</section>
@endsection
