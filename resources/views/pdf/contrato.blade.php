<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contrato de Alquiler - {{ $booking->booking_number }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        h1 { color: #2563eb; text-transform: uppercase; font-size: 18px; margin-bottom: 0; }
        h2 { font-size: 14px; margin-top: 15px; margin-bottom: 5px; border-bottom: 1px solid #ccc; padding-bottom: 3px; }
        .header { margin-bottom: 20px; text-align: center; }
        .row { width: 100%; clear: both; margin-bottom: 15px; }
        .col-half { float: left; width: 48%; margin-right: 2%; }
        .col-half:last-child { margin-right: 0; }
        .box { border: 1px solid #e2e8f0; padding: 10px; border-radius: 4px; }
        .label { font-size: 9px; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 2px; }
        .value { font-weight: bold; font-size: 12px; }
        .clauses { font-size: 9px; color: #555; text-align: justify; margin-top: 20px; }
        .signatures { margin-top: 40px; width: 100%; }
        .sig-box { float: left; width: 45%; text-align: center; margin-right: 5%; }
        .sig-line { border-bottom: 1px solid #000; height: 50px; margin-bottom: 5px; }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CONTRATO DE ALQUILER DE VEHÍCULO</h1>
        <p><strong>{{ $companyName ?? 'Extrarent' }}</strong> - NIF: {{ $companyNif ?? 'B12345678' }}<br>
        {{ $companyAddress ?? '' }} | {{ $companyPhone ?? '' }}</p>
        <p style="font-weight: bold; font-size: 14px;">Localizador: {{ $booking->booking_number }}</p>
    </div>

    <div class="row">
        <div class="col-half box">
            <h2>Datos del Arrendatario (Cliente)</h2>
            <span class="label">Nombre y Apellidos</span>
            <span class="value">{{ $booking->customer->first_name }} {{ $booking->customer->last_name }}</span><br>
            <span class="label">NIF/Pasaporte</span>
            <span class="value">{{ $booking->customer->nif_cif }}</span><br>
            <span class="label">Dirección</span>
            <span class="value">{{ $booking->customer->address }}, {{ $booking->customer->city }}</span><br>
            <span class="label">Teléfono / Email</span>
            <span class="value">{{ $booking->customer->phone }} / {{ $booking->customer->email }}</span>
        </div>
        
        <div class="col-half box">
            <h2>Datos del Vehículo</h2>
            <span class="label">Categoría / Modelo</span>
            <span class="value">{{ $booking->vehicle->name }}</span><br>
            <span class="label">Unidad (Matrícula)</span>
            <span class="value">{{ $booking->unit ? $booking->unit->license_plate : 'No asignada' }}</span><br>
            <span class="label">Nº Bastidor</span>
            <span class="value">{{ $booking->unit ? $booking->unit->vin : 'N/A' }}</span><br>
            <span class="label">Extras</span>
            <span class="value">{{ $booking->unit && $booking->unit->extras ? implode(', ', $booking->unit->extras) : 'Ninguno' }}</span>
        </div>
    </div>
    <div class="clear"></div>

    <div class="row">
        <div class="box">
            <h2>Detalles del Alquiler</h2>
            <div class="col-half">
                <span class="label">Fecha y Hora de Recogida</span>
                <span class="value">{{ $booking->start_date ? $booking->start_date->format('d/m/Y H:i') : '' }}</span><br>
                <span class="label">Fianza Retenida</span>
                <span class="value">{{ number_format($booking->deposit_amount, 2) }} €</span>
            </div>
            <div class="col-half">
                <span class="label">Fecha y Hora de Devolución</span>
                <span class="value">{{ $booking->end_date ? $booking->end_date->format('d/m/Y H:i') : '' }}</span><br>
                <span class="label">Total a Pagar</span>
                <span class="value">{{ number_format($booking->total_amount, 2) }} €</span>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    
    <h2>Cláusulas y Condiciones</h2>
    <div class="clauses">
        {!! $clauses !!}
    </div>

    <div class="signatures">
        <div class="sig-box">
            <div class="sig-line"></div>
            Firma del Arrendatario (Cliente)
        </div>
        <div class="sig-box">
            <div class="sig-line"></div>
            Firma de la Empresa
        </div>
        <div class="clear"></div>
    </div>
</body>
</html>
