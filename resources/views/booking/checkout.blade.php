@extends('layouts.app')

@section('content')
<!-- Header Banner -->
<div style="background: linear-gradient(135deg, #161829 0%, #292D45 100%); padding: 60px 0;">
    <div style="max-width: 1200px; margin: 0 auto; text-align: center; padding: 0 20px;">
        <h1 style="font-size: 40px; font-weight: 800; color: #ffffff; margin-bottom: 15px; font-family: 'Space Grotesk', sans-serif;">{{ __('Finalizar Reserva') }}</h1>
        <p style="font-size: 16px; color: #94a3b8; max-width: 600px; margin: 0 auto;">{{ __('Completa tus datos para confirmar el alquiler de tu vehículo.') }}</p>
    </div>
</div>

<div style="background: #F4F5F6; min-height: calc(100vh - 200px); padding-bottom: 60px;">
    <div style="max-width: 800px; margin: -30px auto 0; padding: 0 20px; position: relative; z-index: 10;">
        @livewire('booking-form', [
            'vehicle_id' => $vehicle_id, 
            'start_date' => $pickup_date, 
            'end_date' => $dropoff_date
        ])
    </div>
</div>
@endsection
