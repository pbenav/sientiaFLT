<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Ticket {{ $doc->numero }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Courier', 'Arial', sans-serif;
            font-size: 10px;
            width: 70mm;
            margin: 0;
            padding: 5mm;
            /* Igualamos márgenes izquierda y derecha a 5mm */
            line-height: 1.2;
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

        .info-table {
            width: 100%;
            margin-bottom: 5px;
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

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 8px;
        }
    </style>
</head>

<body>
    <div class="header">
        <span class="company-name">{{ App\Models\Setting::get('pdf_logo_text', 'sienteERP System') }}</span>
        {!! App\Models\Setting::get('pdf_header_html', 'NIF: B12345678') !!}
    </div>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td>{{ strtoupper($doc->tipo) }}:</td>
            <td class="text-right"><strong>{{ $doc->numero }}</strong></td>
        </tr>
        <tr>
            <td>Fecha:</td>
            <td class="text-right">{{ $doc->fecha->format('d/m/Y H:i') }}</td>
        </tr>
        @if ($doc->tercero)
            <tr>
                <td width="30%">Cliente:</td>
                <td class="text-right">{{ substr($doc->tercero->nombre_comercial ?? $doc->tercero->nombre_razon_social, 0, 20) }}</td>
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
            @foreach ($doc->lineas as $linea)
                <tr>
                    <td>{{ $linea->descripcion }}</td>
                    <td class="text-right">{{ (int) $linea->cantidad }}</td>
                    <td class="text-right">{{ number_format($linea->total, 2, ',', '.') }}€</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <table class="totals-table">
        <tr>
            <td>Subtotal:</td>
            <td class="text-right">{{ number_format($doc->subtotal, 2, ',', '.') }}€</td>
        </tr>
        <tr>
            <td>IVA:</td>
            <td class="text-right">{{ number_format($doc->iva, 2, ',', '.') }}€</td>
        </tr>
        <tr class="total-row">
            <td>TOTAL:</td>
            <td class="text-right">{{ number_format($doc->total, 2, ',', '.') }}€</td>
        </tr>
    </table>

    <div class="footer">
        {{ App\Models\Setting::get('pdf_footer_text', '¡Gracias por su confianza!') }}
        <br>
        Sientia ERP - {{ config('app.version') }}
        @if ($doc->verifactu_qr_url)
            <div class="divider"></div>
            <div class="text-center" style="margin-top: 5px;">
                <div style="font-size: 8px; margin-bottom: 5px; font-weight: bold;">Factura verificable en la sede
                    electr&oacute;nica de la AEAT</div>
                <img src="data:image/svg+xml;base64,{!! base64_encode(
                    SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(100)->generate($doc->verifactu_qr_url),
                ) !!}">
                <div style="font-size: 7px; margin-top: 5px; font-family: monospace; color: #333;">Huella:
                    {{ substr($doc->verifactu_huella, 0, 20) }}...</div>
            </div>
        @endif
    </div>
</body>

</html>
