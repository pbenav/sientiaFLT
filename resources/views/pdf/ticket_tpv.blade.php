<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; font-size: 10px; color: #000; width: 70mm; }
        .center { text-align: center; }
        .right { text-align: right; }
        .divider { border-top: 1px dashed #000; margin: 4px 0; }
        .bold { font-weight: bold; }
        .header { text-align: center; padding: 5px 0; }
        .company { font-size: 14px; font-weight: bold; }
        .info-table { width: 100%; margin: 5px 0; }
        .info-table td { padding: 1px 0; vertical-align: top; }
        table { width: 100%; border-collapse: collapse; margin: 5px 0; }
        th { text-align: left; border-bottom: 1px solid #000; padding: 2px 0; }
        td { padding: 2px 0; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals { margin: 5px 0; }
        .total-row { display: flex; justify-content: space-between; padding: 1px 0; }
        .total-row.grand { font-size: 12px; font-weight: bold; border-top: 1px solid #000; padding-top: 4px; margin-top: 2px; }
        .payment-box { border: 1px dashed #000; padding: 5px; margin: 5px 0; }
        .footer { text-align: center; margin-top: 10px; font-size: 8px; }
        .label { color: #555; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">{{ $companyName }}</div>
        <div>NIF: {{ $companyNif }}</div>
        <div>TICKET TPV</div>
    </div>

    <table class="info-table">
        <tr>
            <td style="width:40%">Ticket:</td>
            <td>{{ $ticket->numero }}</td>
        </tr>
        <tr>
            <td>Fecha:</td>
            <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Operador:</td>
            <td>{{ $ticket->user->name ?? 'N/A' }}</td>
        </tr>
        @if($ticket->customer)
        <tr>
            <td>Cliente:</td>
            <td>{{ $ticket->customer->nombre }} {{ $ticket->customer->apellidos }}</td>
        </tr>
        @endif
    </table>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th style="width:55%">Desc.</th>
                <th style="width:15%" class="text-center">Cant.</th>
                <th style="width:30%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ticket->lineas as $linea)
            <tr>
                <td>
                    {{ $linea->vehicle_name ?? $linea->description ?? 'Alquiler' }}
                    <div style="font-size:8px; color:#555;">IVA: {{ $linea->tax_rate }}%</div>
                </td>
                <td class="text-center">{{ $linea->quantity }}</td>
                <td class="text-right">{{ number_format($linea->total, 2) }}€</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <div class="totals">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>{{ number_format($ticket->subtotal, 2) }}€</span>
        </div>
        <div class="total-row">
            <span>IVA:</span>
            <span>{{ number_format($ticket->iva_total, 2) }}€</span>
        </div>
        @if($ticket->descuento_importe > 0)
        <div class="total-row">
            <span>Descuento:</span>
            <span>-{{ number_format($ticket->descuento_importe, 2) }}€</span>
        </div>
        @endif
        <div class="total-row grand">
            <span>TOTAL:</span>
            <span>{{ number_format($ticket->total, 2) }}€</span>
        </div>
    </div>

    @if($ticket->status === 'completed')
    <div class="payment-box">
        <div class="total-row">
            <span class="label">Pagado:</span>
            <span>{{ $ticket->payment_method ?? 'N/A' }}</span>
        </div>
        <div class="total-row">
            <span class="label">Importe:</span>
            <span>{{ number_format($ticket->amount_paid ?? 0, 2) }}€</span>
        </div>
        @if($ticket->change_given > 0)
        <div class="total-row">
            <span class="label">Cambio:</span>
            <span>{{ number_format($ticket->change_given, 2) }}€</span>
        </div>
        @endif
    </div>
    @endif

    <div class="divider"></div>

    @if($ticket->notes)
    <div style="margin:5px 0; font-size:9px;">
        <span class="label">Notas:</span> {{ $ticket->notes }}
    </div>
    @endif

    <div class="footer">
        Gracias por su compra<br>
        sientiaFLT POS System
    </div>
</body>
</html>
