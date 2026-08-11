@php
    $currencySymbol = '€';
    $currencyPosition = 'suffix';

    // Determinar si es un documento de compra
    $esCompra = false;

    // Etiqueta del tercero según el tipo de documento
    $labelTercero = $esCompra ? 'COMPRADOR (Nuestra empresa)' : 'CLIENTE';

    $labelTipo = match ($doc->type) {
        'factura' => 'FACTURA',
        'abono' => 'FACTURA RECTIFICATIVA',
        'proforma' => 'PROFORMA',
        default => strtoupper($doc->type ?? 'FACTURA'),
    };

    function formatMoney($amount, $symbol, $position)
    {
        $formattedAmount = number_format($amount, 2, ',', '.');
        return $position === 'suffix' ? $formattedAmount . ' ' . $symbol : $symbol . ' ' . $formattedAmount;
    }
@endphp

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $doc->numero }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            position: relative;
        }

        .header {
            margin-bottom: 30px;
        }

        .company-info {
            float: left;
            width: 50%;
        }

        .doc-info {
            float: right;
            width: 40%;
            text-align: right;
        }

        .clear {
            clear: both;
        }

        .billing-info {
            margin-bottom: 30px;
            border-top: 2px solid #eee;
            padding-top: 15px;
        }

        .client-info {
            float: left;
            width: 45%;
        }

        .delivery-info {
            float: right;
            width: 45%;
        }

        h1 {
            margin: 0;
            color: #2563eb;
            text-transform: uppercase;
            font-size: 24px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .table th {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            padding: 10px;
            text-align: left;
        }

        .table td {
            border-bottom: 1px solid #e2e8f0;
            padding: 10px;
        }

        .text-right {
            text-align: right;
        }

        .totals {
            float: right;
            width: 30%;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }

        .grand-total {
            border-top: 2px solid #2563eb;
            margin-top: 10px;
            padding-top: 10px;
            font-weight: bold;
            font-size: 16px;
            color: #2563eb;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 10px;
            color: #94a3b8;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }

        /* Marca de agua para documentos anulados */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            font-weight: bold;
            color: rgba(239, 68, 68, 0.2);
            text-transform: uppercase;
            z-index: 1000;
            pointer-events: none;
            white-space: nowrap;
            letter-spacing: 10px;
        }
    </style>
</head>

<body>
    {{-- Marca de agua para documentos anulados --}}
    @if ($doc->status === 'cancelled')
        <div class="watermark">ANULADA</div>
    @endif

    <div class="header">
        <div class="company-info">
            <h1>{{ $companyName ?? 'Extrarent' }}</h1>
            <p>
                <strong>{{ $companyName ?? 'Extrarent' }}</strong><br>
                NIF: {{ $companyNif ?? 'B12345678' }}<br>
                {{ $companyAddress ?? 'Dirección' }}<br>
                {{ $companyPhone ?? 'Teléfono' }}<br>
                {{ $companyEmail ?? 'Email' }}
            </p>
        </div>
        <div class="doc-info">
            <h2 style="color: #64748b; margin: 0;">{{ $labelTipo }}</h2>
            <p style="font-size: 18px; font-weight: bold; margin: 5px 0;">{{ $doc->invoice_number }}</p>
            <p>Fecha de emisión: {{ $doc->issue_date ? $doc->issue_date->format('d/m/Y') : '' }}</p>
            @if ($doc->due_date)
                <p>Fecha de vencimiento: {{ $doc->due_date->format('d/m/Y') }}</p>
            @endif
            @if ($doc->booking)
                <p>Reserva asociada: {{ $doc->booking->booking_number }}</p>
            @endif
        </div>
        <div class="clear"></div>
    </div>

    <div class="billing-info">
        <div class="delivery-info">
            @if ($doc->customer)
                <strong>{{ $doc->customer->first_name }} {{ $doc->customer->last_name }}</strong><br>
                NIF/CIF: {{ $doc->customer->nif_cif }}<br>
                {{ $doc->customer->address }}<br>
                {{ $doc->customer->city }}, {{ $doc->customer->country }}<br>
                Email: {{ $doc->customer->email }}
            @else
                <strong>[CLIENTE NO DEFINIDO]</strong>
            @endif
        </div>
        <div class="clear"></div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="50%">Descripción</th>
                <th width="10%" class="text-right">Cant.</th>
                <th width="20%" class="text-right">Precio</th>
                <th width="20%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @if ($doc->booking && $doc->booking->vehicle)
                <tr>
                    <td>
                        <strong>Alquiler Vehículo</strong> - {{ $doc->booking->vehicle->name }} 
                        <br><span style="font-size: 10px; color: #666;">( {{ $doc->booking->start_date ? $doc->booking->start_date->format('d/m/Y') : '' }} al {{ $doc->booking->end_date ? $doc->booking->end_date->format('d/m/Y') : '' }} )</span>
                    </td>
                    <td class="text-right">1</td>
                    <td class="text-right">
                        {{ formatMoney($doc->subtotal, $currencySymbol, $currencyPosition) }}</td>
                    <td class="text-right">{{ formatMoney($doc->subtotal, $currencySymbol, $currencyPosition) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div style="width: 100%;">
        <div style="float: left; width: 60%;">
            @if ($doc->observaciones)
                <h4 style="margin-bottom: 5px;">Observaciones:</h4>
                <p style="font-size: 10px;">{{ $doc->observaciones }}</p>
            @endif
        </div>
        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span class="text-right">{{ formatMoney($doc->subtotal, $currencySymbol, $currencyPosition) }}</span>
            </div>
            <div class="total-row">
                <span>IVA:</span>
                <span class="text-right">{{ formatMoney($doc->tax_amount, $currencySymbol, $currencyPosition) }}</span>
            </div>
            <div class="grand-total">
                <span>TOTAL:</span>
                <span style="float: right;">{{ formatMoney($doc->total_amount, $currencySymbol, $currencyPosition) }}</span>
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="footer">
        Extrarent Rent-a-Car
    </div>
</body>

</html>
