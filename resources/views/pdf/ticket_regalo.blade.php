<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Ticket Regalo {{ $ticket->numero }}</title>
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

        .text-right {
            text-align: right;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 8px;
        }

        .gift-notice {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            margin-top: 10px;
            border: 1px solid #000;
            padding: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <span class="company-name">{{ App\Models\Setting::get('pdf_logo_text', 'sienteERP POS') }}</span>
        {!! App\Models\Setting::get('pdf_header_html', 'NIF: B12345678') !!}
    </div>

    <div class="gift-notice">TICKET REGALO</div>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td>TICKET:</td>
            <td class="text-right"><strong>{{ $ticket->numero }}</strong></td>
        </tr>
        <tr>
            <td>Fecha:</td>
            <td class="text-right">{{ $ticket->created_at->format('d/m/Y') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="80%">Art&iacute;culo</th>
                <th width="20%" class="text-right">Cant.</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ticket->items as $item)
                <tr>
                    <td>{{ $item->product ? $item->product->name : 'Producto' }}</td>
                    <td class="text-right">{{ (int) $item->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <div class="footer">
        {{ App\Models\Setting::get('pdf_footer_text', '¡Esperamos que le guste el regalo!') }}
        <br>
        Este ticket solo es v&aacute;lido para cambios o devoluciones.
        <br>
        Sientia ERP - 1.0.4 Rescue Linear
    </div>
</body>

</html>
