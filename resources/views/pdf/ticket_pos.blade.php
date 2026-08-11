<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Ticket TPV {{ $ticket->numero }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Courier', 'Arial', sans-serif;
            font-size: {{ ($width ?? '80mm') === '58mm' ? '8px' : '10px' }};
            width: {{ ($width ?? '80mm') === '58mm' ? '48mm' : '70mm' }};
            margin: 0;
            padding: 2mm;
            line-height: 1.1;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .company-name {
            font-weight: bold;
            font-size: 14px;
            display: block;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        /* Usamos tablas para alineación perfecta en dompdf (flex no es fiable) */
        .info-table {
            width: 100%;
            margin-bottom: 2px;
        }

        .info-table td {
            padding: 1px 0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .items-table th {
            text-align: left;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }

        .items-table td {
            vertical-align: top;
            padding: 3px 0;
        }

        .totals-table {
            width: 100%;
            margin-top: 5px;
        }

        .totals-table td {
            padding: 1px 0;
        }

        .total-row {
            font-weight: bold;
            font-size: 12px;
            border-top: 1px double #000;
        }

        .total-row td {
            padding-top: 5px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 8px;
        }
    </style>
</head>

<body>
    <div class="header">
        <span class="company-name">{{ $companyName ?? 'Extrarent' }}</span>
        {!! 'NIF: ' . ($companyNif ?? 'B12345678') !!}
    </div>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td width="30%">TICKET:</td>
            <td class="text-right"><strong>{{ $ticket->booking_number }}</strong></td>
        </tr>
        <tr>
            <td>Fecha:</td>
            <td class="text-right">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Operador:</td>
            <td class="text-right">{{ $ticket->user ? $ticket->user->name : 'Admin' }}</td>
        </tr>
        @if ($ticket->customer)
            <tr>
                <td>Cliente:</td>
                <td class="text-right">{{ substr($ticket->customer->first_name . ' ' . $ticket->customer->last_name, 0, 20) }}</td>
            </tr>
        @endif
    </table>

    <div class="divider"></div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="55%">Desc.</th>
                <th width="15%" class="text-right">Cant.</th>
                <th width="30%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    Alquiler {{ $ticket->vehicle ? $ticket->vehicle->name : 'Vehículo' }}
                    <div style="font-size: 8px; color: #000;">{{ $ticket->start_date ? $ticket->start_date->format('d/m/y') : '' }} - {{ $ticket->end_date ? $ticket->end_date->format('d/m/y') : '' }}</div>
                </td>
                <td class="text-right">1</td>
                <td class="text-right">{{ number_format($ticket->subtotal, 2, ',', '.') }}€</td>
            </tr>
        </tbody>
    </table>

    <div class="divider"></div>

    <table class="totals-table">
        <tr>
            <td>Subtotal:</td>
            <td class="text-right">{{ number_format($ticket->subtotal, 2, ',', '.') }}€</td>
        </tr>

        <tr style="font-weight: bold;">
            <td>Total IVA:</td>
            <td class="text-right">{{ number_format($ticket->tax_amount, 2, ',', '.') }}€</td>
        </tr>

        @if ($ticket->discount_amount > 0)
            <tr>
                <td>Descuento:</td>
                <td class="text-right">
                    -{{ number_format($ticket->discount_amount, 2, ',', '.') }}€
                </td>
            </tr>
        @endif
        <tr class="total-row">
            <td>TOTAL:</td>
            <td class="text-right">{{ number_format($ticket->total_amount, 2, ',', '.') }}€</td>
        </tr>
    </table>

    <div style="margin-top: 10px; border-top: 1px dashed #000; padding-top: 5px;">
        <table class="info-table" style="font-size: 9px;">
            <tr>
                <td>Pagado:</td>
                <td class="text-right">{{ number_format($ticket->amount_paid, 2, ',', '.') }}€</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        {{ '¡Gracias por su reserva!' }}
        <br>
        Extrarent Rent-a-Car
    </div>
</body>

</html>
