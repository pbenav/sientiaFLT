<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Etiquetas</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', 'Arial', sans-serif;
            background: white;
            color: black;
            font-size: 10px;
        }

        .sheet {
            position: relative;
            width: {{ $format->document_width }}mm;
            height: {{ $format->document_height }}mm;
            margin: 0;
            padding: 0;
        }

        .sheet+.sheet {
            page-break-before: always;
        }

        .label {
            position: absolute;
            width: {{ $format->label_width }}mm;
            height: {{ $format->label_height }}mm;
            box-sizing: border-box;
            padding: 2mm;
            overflow: hidden;
            text-align: center;
            /* border: 0.1mm solid #eee; /* Remove for production */
        }

        .label-content {
            width: 100%;
            height: 100%;
            display: table;
            /* Changed from flex */
            border-collapse: collapse;
            table-layout: fixed;
        }

        .label-content td {
            display: table-cell;
            /* Changed from flex */
            vertical-align: middle;
            text-align: center;
        }

        .product-name {
            font-weight: bold;
            font-size: 8px;
            margin-bottom: 2px;
            line-height: 1.1;
            overflow: hidden;
            {{ $displayUppercase ? 'text-transform: uppercase;' : '' }}
        }

        .sku {
            font-size: 7px;
            color: #555;
            margin-bottom: 2px;
        }

        .price {
            font-weight: bold;
            font-size: 14px;
            margin-top: 2px;
        }

        .barcode {
            margin-top: 2px;
            width: 100%;
            height: 10mm;
            /* Increased slightly for better visibility */
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .barcode img {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            height: auto;
            max-height: 100%;
        }

        .qr-code-img {
            aspect-ratio: 1 / 1;
        }
    </style>
</head>

<body>
    @php
        $labelsPerSheet = $format->labels_per_sheet ?: $format->labels_per_row * $format->labels_per_column;
        $totalLabels = count($labels);
        $sheets = array_chunk($labels, $labelsPerSheet);
    @endphp

    @foreach ($sheets as $sheetLabels)
        <div class="sheet">
            @foreach ($sheetLabels as $index => $label)
                @php
                    $row = floor($index / $format->labels_per_row);
                    $col = $index % $format->labels_per_row;

                    $top = $format->margin_top + $row * ($format->label_height + $format->vertical_spacing);
                    $left = $format->margin_left + $col * ($format->label_width + $format->horizontal_spacing);
                @endphp
                <div class="label" style="top: {{ $top }}mm; left: {{ $left }}mm;">
                    @if ($label)
                        <table class="label-content">
                            <tr>
                                <td>
                                    <div class="product-name">{{ $label['name'] }}</div>
                                    <div class="sku">{{ $label['sku'] }}</div>

                                    @if ($label['barcode'])
                                        <div class="barcode">
                                            {!! $label['barcode'] !!}
                                        </div>
                                        <div style="font-size: 6px;">{{ $label['barcode_text'] }}</div>
                                    @endif

                                    @if ($showPrice)
                                        <div class="price">{{ number_format($label['price'], 2, ',', '.') }} €</div>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
</body>

</html>
