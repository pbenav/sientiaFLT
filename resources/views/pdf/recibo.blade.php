<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Recibo {{ $doc->serie }}-{{ $doc->numero ?? 'BORRADOR' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header-table {
            width: 100%;
            margin-bottom: 40px;
        }
        .logo-container {
            width: 60%;
            vertical-align: top;
        }
        .amount-container {
            width: 40%;
            text-align: right;
            vertical-align: top;
        }
        .amount-box {
            border: 2px solid #ccc;
            padding: 15px;
            background-color: #f9f9f9;
            display: inline-block;
            min-width: 200px;
            text-align: center;
        }
        .amount-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .amount-value {
            font-size: 24px;
            font-weight: bold;
            color: #000;
        }
        
        /* Ventana para sobre estándar (DL: 110x220mm)
           Posición típica ventana izquierda: 20mm izq, 45mm arriba */
        .envelope-window {
            border: 1px dotted #ccc; /* Dotted para guia, o solido si se quiere remarcar la caja */
            padding: 15px;
            width: 85mm;
            height: 35mm;
            margin-top: 20px;
            margin-bottom: 40px;
            border-radius: 5px;
        }
        .client-name {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .client-address {
            font-size: 14px;
            line-height: 1.4;
        }
        
        .receipt-body {
            margin-top: 30px;
            line-height: 1.8;
            text-align: justify;
            font-size: 16px;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
            padding: 40px 0;
        }
        .highlight {
            font-weight: bold;
            border-bottom: 1px dashed #999;
            padding: 0 5px;
        }
        
        .footer-info {
            margin-top: 40px;
            width: 100%;
        }
        .footer-cell {
            width: 33%;
            vertical-align: top;
        }
        .signature-box {
            margin-top: 50px;
            border-top: 1px solid #333;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            padding-top: 10px;
            font-size: 12px;
        }
        
        .meta-info {
            font-size: 12px;
            color: #777;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    @php
        $logoType = \App\Models\Setting::get('pdf_logo_type', 'text');
        $logoText = \App\Models\Setting::get('pdf_logo_text', 'sienteERP System');
        $logoImage = \App\Models\Setting::get('pdf_logo_image');
        
        $empresaNombre = $logoText; // O usar setting específico de nombre fiscal
        $empresaDireccion = \App\Models\Setting::get('app_address', 'Dirección de la Empresa'); // Asumimos que existe o fallback
    @endphp

    <table class="header-table">
        <tr>
            <td class="logo-container">
                @if($logoType === 'image' && $logoImage)
                    <img src="{{ public_path('storage/' . $logoImage) }}" alt="Logo" style="max-height: 60px;">
                @else
                    <h1 style="margin: 0; color: #444;">{{ $logoText }}</h1>
                @endif
                <div class="meta-info">
                    <strong>RECIBO Nº:</strong> {{ $doc->serie }}-{{ $doc->numero }}<br>
                    <strong>FECHA EMISIÓN:</strong> {{ $doc->fecha ? $doc->fecha->format('d/m/Y') : 'Borrador' }}
                </div>
            </td>
            <td class="amount-container">
                <div class="amount-box">
                    <div class="amount-label">IMPORTE</div>
                    <div class="amount-value">{{ number_format($doc->total, 2, ',', '.') }} €</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="envelope-window">
        <!-- Datos del Cliente para Ventanilla -->
        @if ($doc->tercero)
            <div class="client-name">{{ $doc->tercero->nombre_comercial ?? $doc->tercero->nombre_razon_social }}</div>
            <div class="client-address">
                {{ $doc->tercero->direccion ?? '' }}<br>
                {{ $doc->tercero->codigo_postal ?? '' }} {{ $doc->tercero->ciudad ?? '' }}<br>
                {{ $doc->tercero->provincia ?? '' }}
            </div>
        @else
            <div class="client-name">[CLIENTE NO DEFINIDO]</div>
        @endif
    </div>

    <div class="receipt-body">
        @if($doc->tipo === 'recibo_compra')
        <p>
            El proveedor <span class="highlight">{{ $doc->tercero->nombre_razon_social ?? $doc->tercero->nombre_comercial ?? '[NO DEFINIDO]' }}</span>
            con NIF/CIF {{ $doc->tercero->nif_cif ?? '___________' }},
        </p>
        <p>
            certifica que ha recibido la cantidad de <span class="highlight">{{ $importeLetras }}</span>
        </p>
        <p>
            por la entrega del material referenciado en la factura nº <span class="highlight">{{ $doc->documentoOrigen->numero ?? '___________' }}</span>
            de fecha {{ $doc->documentoOrigen && $doc->documentoOrigen->fecha ? $doc->documentoOrigen->fecha->format('d/m/Y') : '___________' }}
            por medio de este recibo.
        </p>
        @else
        <p>
            He recibido de <span class="highlight">{{ $doc->tercero->nombre_razon_social ?? $doc->tercero->nombre_comercial ?? '[NO DEFINIDO]' }}</span>
            con NIF/CIF {{ $doc->tercero->nif_cif ?? '___________' }},
        </p>
        <p>
            la cantidad de <span class="highlight">{{ $importeLetras }}</span>
        </p>
        <p>
            correspondiente al pago de la Factura
            número <span class="highlight">{{ $doc->documentoOrigen->numero ?? '___________' }}</span>
            de fecha {{ $doc->documentoOrigen && $doc->documentoOrigen->fecha ? $doc->documentoOrigen->fecha->format('d/m/Y') : '___________' }}.
        </p>
        @endif
        
        @if($doc->observaciones)
        <p style="margin-top: 20px; font-size: 14px; color: #555;">
            <strong>Concepto / Observaciones:</strong><br>
            {{ $doc->observaciones }}
        </p>
        @endif
    </div>

    <table class="footer-info">
        <tr>
            <td class="footer-cell">
                <strong>Forma de Pago:</strong><br>
                {{ $doc->formaPago->nombre ?? 'No especificada' }}
            </td>
            <td class="footer-cell">
                <strong>Vencimiento:</strong><br>
                {{ $doc->fecha_vencimiento ? $doc->fecha_vencimiento->format('d/m/Y') : 'Al contado' }}
            </td>
            <td class="footer-cell" style="text-align: center;">
                <div class="signature-box">
                    Firma y Sello<br>
                    <strong>{{ $doc->tipo === 'recibo_compra' ? ($doc->tercero->nombre_razon_social ?? $doc->tercero->nombre_comercial ?? '[NO DEFINIDO]') : $empresaNombre }}</strong>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
