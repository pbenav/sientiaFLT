<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmación de Reserva</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eaeaea; border-radius: 8px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #161829; font-size: 24px; }
        .locator { background: #f4f5f6; padding: 15px; text-align: center; border-radius: 6px; margin-bottom: 30px; font-size: 20px; letter-spacing: 2px; color: #0056DE; font-weight: bold; }
        .details { margin-bottom: 30px; }
        .details th { text-align: left; padding: 8px 0; border-bottom: 1px solid #eaeaea; width: 40%; color: #6e7684; font-weight: normal; }
        .details td { padding: 8px 0; border-bottom: 1px solid #eaeaea; font-weight: bold; }
        .footer { text-align: center; color: #999; font-size: 12px; margin-top: 40px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Hola {{ $booking->customer->first_name }}!</h1>
            <p>Tu reserva ha sido confirmada correctamente.</p>
        </div>

        <div class="locator">
            Localizador: {{ $booking->booking_number }}
        </div>

        <table class="details" width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <th>Vehículo</th>
                <td>{{ $booking->vehicle->name }}</td>
            </tr>
            <tr>
                <th>Fecha Recogida</th>
                <td>{{ $booking->start_date->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Fecha Devolución</th>
                <td>{{ $booking->end_date->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Precio Total</th>
                <td style="color: #EA001E;">€{{ number_format($booking->total_amount, 2) }}</td>
            </tr>
            @if($booking->deposit_amount > 0)
            <tr>
                <th>Fianza (en destino)</th>
                <td>€{{ number_format($booking->deposit_amount, 2) }}</td>
            </tr>
            @endif
        </table>

        <p>Por favor, guarda este localizador y preséntalo en nuestra oficina el día de la recogida. Si tienes alguna duda, responde a este correo o contáctanos por WhatsApp.</p>
        
        <p>¡Gracias por elegirnos!<br>El equipo de Extrarent</p>

        <div class="footer">
            &copy; {{ date('Y') }} Extrarent Ibiza. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
