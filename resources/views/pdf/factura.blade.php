<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #333; }
        .header { display: flex; justify-content: space-between; padding: 20px 0; border-bottom: 3px solid #2c3e50; margin-bottom: 20px; }
        .company-info { flex: 1; }
        .company-name { font-size: 22px; font-weight: bold; color: #2c3e50; }
        .company-details { font-size: 9px; color: #666; margin-top: 4px; line-height: 1.5; }
        .doc-info { text-align: right; }
        .doc-type { font-size: 18px; font-weight: bold; color: #2c3e50; text-transform: uppercase; letter-spacing: 2px; }
        .doc-number { font-size: 13px; margin-top: 4px; color: #2c3e50; }
        .doc-date { font-size: 10px; color: #666; margin-top: 2px; }
        .info-section { display: flex; gap: 20px; margin-bottom: 20px; }
        .info-box { flex: 1; padding: 12px; background: #f8f9fa; border-radius: 4px; border: 1px solid #e9ecef; }
        .info-box h4 { font-size: 10px; font-weight: bold; color: #2c3e50; text-transform: uppercase; margin-bottom: 6px; padding-bottom: 4px; border-bottom: 1px solid #dee2e6; }
        .info-box p { font-size: 10px; margin-bottom: 2px; line-height: 1.4; }
        .info-box .name { font-weight: bold; font-size: 11px; }
        .table-section { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #2c3e50; color: white; }
        th { padding: 8px 10px; text-align: left; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        td { padding: 8px 10px; border-bottom: 1px solid #e9ecef; font-size: 10px; }
        tbody tr:nth-child(even) { background: #f8f9fa; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals-section { display: flex; justify-content: flex-end; margin-bottom: 20px; }
        .totals-box { width: 300px; }
        .total-row { display: flex; justify-content: space-between; padding: 4px 0; font-size: 10px; }
        .total-row.grand-total { font-size: 14px; font-weight: bold; color: #2c3e50; border-top: 2px solid #2c3e50; padding-top: 8px; margin-top: 4px; }
        .payment-section { background: #f8f9fa; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #e9ecef; }
        .payment-section h4 { font-size: 10px; font-weight: bold; color: #2c3e50; margin-bottom: 8px; }
        .payment-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4px 20px; }
        .payment-grid .label { font-size: 9px; color: #666; }
        .payment-grid .value { font-size: 10px; font-weight: bold; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 8px; color: #999; padding: 10px; border-top: 1px solid #e9ecef; }
        .vat-breakdown { margin-bottom: 20px; padding: 10px; background: #f0f7ff; border: 1px solid #bee3eb; border-radius: 4px; }
        .vat-breakdown h4 { font-size: 10px; font-weight: bold; color: #2c3e50; margin-bottom: 6px; }
        .vat-row { display: flex; justify-content: space-between; font-size: 10px; padding: 2px 0; }
        .observations { margin-bottom: 20px; padding: 10px; background: #fffbe6; border: 1px solid #ffe58f; border-radius: 4px; }
        .observations h4 { font-size: 10px; font-weight: bold; margin-bottom: 4px; }
        .observations p { font-size: 9px; line-height: 1.4; }
        .vat-row .rate { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <div class="company-name">{{ $companyName }}</div>
            <div class="company-details">
                {{ $companyNif }}<br>
                {{ $companyAddress }}<br>
                Tel: {{ $companyPhone }} | {{ $companyEmail }}
            </div>
        </div>
        <div class="doc-info">
            <div class="doc-type">FACTURA</div>
            <div class="doc-number">Nº {{ $alquiler->alquiler_number }}</div>
            <div class="doc-date">Fecha emision: {{ $alquiler->created_at->format('d/m/Y') }}</div>
            <div class="doc-date">Vencimiento: {{ now()->addDays(30)->format('d/m/Y') }}</div>
        </div>
    </div>

    <div class="info-section">
        <div class="info-box">
            <h4>Datos Fiscales (Emisor)</h4>
            <p class="name">{{ $companyName }}</p>
            <p>NIF: {{ $companyNif }}</p>
            <p>{{ $companyAddress }}</p>
            <p>{{ $companyPhone }}</p>
            <p>{{ $companyEmail }}</p>
        </div>
        <div class="info-box">
            <h4>Datos Fiscales (Cliente)</h4>
            <p class="name">{{ $alquiler->customer->full_name ?? 'N/A' }}</p>
            <p>NIF/CIF: {{ $alquiler->customer->nif_cif ?? 'N/A' }}</p>
            <p>{{ $alquiler->customer->address ?? '' }}</p>
            <p>{{ $alquiler->customer->postal_code ?? '' }} {{ $alquiler->customer->city ?? '' }}</p>
            <p>Tel: {{ $alquiler->customer->phone ?? '' }}</p>
            <p>Email: {{ $alquiler->customer->email ?? '' }}</p>
        </div>
    </div>

    <div class="table-section">
        <table>
            <thead>
                <tr>
                    <th style="width:50%">Descripcion</th>
                    <th style="width:8%" class="text-center">Cant.</th>
                    <th style="width:12%" class="text-right">P. Unitario</th>
                    <th style="width:8%" class="text-right">Dto.</th>
                    <th style="width:10%" class="text-right">Base</th>
                    <th style="width:7%" class="text-right">IVA</th>
                    <th style="width:15%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alquiler->ticketTPV->lineas as $linea)
                <tr>
                    <td>{{ $linea->vehicle_name ?? $linea->description ?? 'Alquiler vehiculo' }}</td>
                    <td class="text-center">{{ $linea->quantity }}</td>
                    <td class="text-right">{{ number_format($linea->unit_price, 2) }}</td>
                    <td class="text-right">{{ $linea->discount_percentage }}%</td>
                    <td class="text-right">{{ number_format($linea->subtotal, 2) }}</td>
                    <td class="text-right">{{ $linea->tax_rate }}%</td>
                    <td class="text-right">{{ number_format($linea->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="vat-breakdown">
        <h4>Desglose de IVA</h4>
        <div class="vat-row">
            <span class="rate">Base Imponible:</span>
            <span>{{ number_format($alquiler->base_imponible, 2) }} EUR</span>
        </div>
        <div class="vat-row">
            <span class="rate">IVA (21%):</span>
            <span>{{ number_format($alquiler->iva, 2) }} EUR</span>
        </div>
    </div>

    <div class="totals-section">
        <div class="totals-box">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>{{ number_format($alquiler->subtotal, 2) }} EUR</span>
            </div>
            <div class="total-row">
                <span>Descuento:</span>
                <span>-{{ number_format($alquiler->descuento, 2) }} EUR</span>
            </div>
            <div class="total-row">
                <span>Base Imponible:</span>
                <span>{{ number_format($alquiler->base_imponible, 2) }} EUR</span>
            </div>
            <div class="total-row">
                <span>IVA (21%):</span>
                <span>{{ number_format($alquiler->iva, 2) }} EUR</span>
            </div>
            <div class="total-row grand-total">
                <span>TOTAL:</span>
                <span>{{ number_format($alquiler->total, 2) }} EUR</span>
            </div>
        </div>
    </div>

    <div class="payment-section">
        <h4>Informacion de Pago</h4>
        <div class="payment-grid">
            <span class="label">Estado Pago:</span>
            <span class="value">{{ ucfirst($alquiler->payment_status) }}</span>
            <span class="label">Total Factura:</span>
            <span class="value">{{ number_format($alquiler->total, 2) }} EUR</span>
            <span class="label">Importe Pagado:</span>
            <span class="value">{{ number_format($alquiler->amount_paid, 2) }} EUR</span>
            <span class="label">Saldo Pendiente:</span>
            <span class="value">{{ number_format($alquiler->amount_due, 2) }} EUR</span>
            <span class="label">Metodo de Pago:</span>
            <span class="value">{{ $alquiler->paymentMethod->nombre ?? 'N/A' }}</span>
            <span class="label">Deposito:</span>
            <span class="value">{{ number_format($alquiler->deposit_amount, 2) }} EUR</span>
        </div>
    </div>

    @if($alquiler->observaciones)
    <div class="observations">
        <h4>Observaciones</h4>
        <p>{{ $alquiler->observaciones }}</p>
    </div>
    @endif

    <div class="footer">
        {{ $companyName }} | {{ $companyNif }} | Factura generada por sientiaFLT el {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
