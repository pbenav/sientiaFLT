<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #333; }
        .header { display: flex; justify-content: space-between; padding: 20px 0; border-bottom: 2px solid #333; margin-bottom: 20px; }
        .company-info { flex: 1; }
        .company-name { font-size: 20px; font-weight: bold; color: #2c3e50; }
        .company-details { font-size: 9px; color: #666; margin-top: 4px; line-height: 1.5; }
        .doc-info { text-align: right; }
        .doc-type { font-size: 16px; font-weight: bold; color: #2c3e50; text-transform: uppercase; }
        .doc-number { font-size: 12px; margin-top: 4px; }
        .doc-date { font-size: 10px; color: #666; margin-top: 2px; }
        .doc-status { display: inline-block; padding: 2px 8px; border-radius: 3px; font-size: 9px; font-weight: bold; margin-top: 4px; }
        .status-borrador { background: #eee; color: #666; }
        .status-confirmado { background: #d6eaf8; color: #2980b9; }
        .status-activo { background: #d5f5e3; color: #27ae60; }
        .status-completado { background: #d5d8dc; color: #555; }
        .status-anulado { background: #fadbd8; color: #c0392b; }
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
        .signature-section { display: flex; justify-content: space-between; margin-top: 60px; padding-top: 20px; }
        .signature-box { width: 200px; text-align: center; }
        .signature-line { border-top: 1px solid #333; margin-top: 40px; padding-top: 4px; font-size: 9px; }
        .observations { margin-bottom: 20px; padding: 10px; background: #fffbe6; border: 1px solid #ffe58f; border-radius: 4px; }
        .observations h4 { font-size: 10px; font-weight: bold; margin-bottom: 4px; }
        .observations p { font-size: 9px; line-height: 1.4; }
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
            <div class="doc-type">ALBARAN DE ENTREGA</div>
            <div class="doc-number">Nº {{ $alquiler->alquiler_number }}</div>
            <div class="doc-date">Fecha: {{ $alquiler->created_at->format('d/m/Y H:i') }}</div>
            <span class="doc-status status-{{ $alquiler->status }}">
                {{ $statusLabels[$alquiler->status] ?? $alquiler->status }}
            </span>
        </div>
    </div>

    <div class="info-section">
        <div class="info-box">
            <h4>Cliente</h4>
            <p class="name">{{ $alquiler->customer->full_name ?? 'N/A' }}</p>
            <p>{{ $alquiler->customer->nif_cif ?? '' }}</p>
            <p>{{ $alquiler->customer->address ?? '' }}</p>
            <p>{{ $alquiler->customer->postal_code ?? '' }} {{ $alquiler->customer->city ?? '' }}</p>
            <p>Tel: {{ $alquiler->customer->phone ?? '' }}</p>
            <p>Email: {{ $alquiler->customer->email ?? '' }}</p>
        </div>
        <div class="info-box">
            <h4>Detalles del Alquiler</h4>
            <p><span style="color:#666">Fecha Entrega:</span> {{ $alquiler->fecha_entrega ? date('d/m/Y', strtotime($alquiler->fecha_entrega)) : 'N/A' }}</p>
            <p><span style="color:#666">Fecha Devolucion:</span> {{ $alquiler->fecha_devolucion ? date('d/m/Y', strtotime($alquiler->fecha_devolucion)) : 'N/A' }}</p>
            <p><span style="color:#666">Ubicacion Entrega:</span> {{ $alquiler->start_location ?? 'N/A' }}</p>
            <p><span style="color:#666">Ubicacion Devolucion:</span> {{ $alquiler->end_location ?? 'N/A' }}</p>
            <p><span style="color:#666">Moneda:</span> {{ $alquiler->currency_code ?? 'EUR' }}</p>
        </div>
    </div>

    <div class="table-section">
        <table>
            <thead>
                <tr>
                    <th style="width:50%">Descripcion</th>
                    <th style="width:10%" class="text-center">Cant.</th>
                    <th style="width:15%" class="text-right">Precio Unit.</th>
                    <th style="width:10%" class="text-right">Dto.</th>
                    <th style="width:15%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alquiler->ticketTPV->lineas as $linea)
                <tr>
                    <td>{{ $linea->vehicle_name ?? $linea->description ?? 'Alquiler vehiculo' }}</td>
                    <td class="text-center">{{ $linea->quantity }}</td>
                    <td class="text-right">{{ number_format($linea->unit_price, 2) }} EUR</td>
                    <td class="text-right">{{ $linea->discount_percentage }}%</td>
                    <td class="text-right">{{ number_format($linea->total, 2) }} EUR</td>
                </tr>
                @endforeach
            </tbody>
        </table>
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
        <h4>Resumen de Pago</h4>
        <div class="payment-grid">
            <span class="label">Estado Pago:</span>
            <span class="value">{{ $paymentStatusLabels[$alquiler->payment_status] ?? $alquiler->payment_status }}</span>
            <span class="label">Total:</span>
            <span class="value">{{ number_format($alquiler->total, 2) }} EUR</span>
            <span class="label">Pagado:</span>
            <span class="value">{{ number_format($alquiler->amount_paid, 2) }} EUR</span>
            <span class="label">Restante:</span>
            <span class="value">{{ number_format($alquiler->amount_due, 2) }} EUR</span>
            <span class="label">Deposito:</span>
            <span class="value">{{ number_format($alquiler->deposit_amount, 2) }} EUR</span>
            <span class="label">Metodo de Pago:</span>
            <span class="value">{{ $alquiler->paymentMethod->nombre ?? 'N/A' }}</span>
        </div>
    </div>

    @if($alquiler->observaciones)
    <div class="observations">
        <h4>Observaciones</h4>
        <p>{{ $alquiler->observaciones }}</p>
    </div>
    @endif

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line">Firma del Cliente</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Sello y Firma de la Empresa</div>
        </div>
    </div>

    <div class="footer">
        {{ $companyName }} | {{ $companyNif }} | Generado por sientiaFLT el {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
