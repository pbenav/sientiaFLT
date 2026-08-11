<?php

namespace App\Services;

use App\Models\Alquiler;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\TicketTPV;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class AlquilerService
{
    public function createFromTicket(int $ticketId, array $data): Alquiler
    {
        return DB::transaction(function () use ($ticketId, $data) {
            $ticket = TicketTPV::findOrFail($ticketId);

            $alquiler = Alquiler::create([
                'ticket_tpv_id' => $ticketId,
                'alquiler_number' => null,
                'customer_id' => $data['customer_id'] ?? $ticket->customer_id,
                'vehicle_id' => $data['vehicle_id'] ?? $ticket->lineas->first()?->vehicle_id,
                'location_id' => $data['location_id'] ?? null,
                'start_date' => $data['start_date'] ?? now(),
                'end_date' => $data['end_date'] ?? now()->addDays(3),
                'status' => 'borrador',
                'payment_status' => 'pendiente',
                'subtotal' => $ticket->subtotal,
                'descuento' => $ticket->descuento_importe,
                'base_imponible' => $ticket->subtotal - $ticket->descuento_importe,
                'iva' => $ticket->iva_total,
                'total' => $ticket->total,
                'amount_paid' => $ticket->amount_paid ?? 0,
                'amount_due' => $ticket->amount_due ?? $ticket->total,
                'deposit_amount' => $data['deposit_amount'] ?? 0,
                'payment_method_id' => $data['payment_method_id'] ?? null,
                'observaciones' => $data['observaciones'] ?? null,
                'fecha_entrega' => $data['fecha_entrega'] ?? now()->toDateString(),
                'fecha_devolucion' => $data['fecha_devolucion'] ?? now()->addDays(3)->toDateString(),
                'currency_code' => 'EUR',
            ]);

            return $alquiler;
        });
    }

    public function confirmarAlquiler(int $alquilerId): Alquiler
    {
        $alquiler = Alquiler::findOrFail($alquilerId);

        if ($alquiler->status === 'borrador') {
            $alquiler->update(['status' => 'confirmado']);
        }

        return $alquiler;
    }

    public function completarAlquiler(int $alquilerId): Alquiler
    {
        return DB::transaction(function () use ($alquilerId) {
            $alquiler = Alquiler::findOrFail($alquilerId);

            $alquiler->update([
                'status' => 'completado',
                'payment_status' => 'pagado',
                'fecha_devolucion' => now()->toDateString(),
            ]);

            return $alquiler;
        });
    }

    public function anularAlquiler(int $alquilerId): Alquiler
    {
        $alquiler = Alquiler::findOrFail($alquilerId);

        if ($alquiler->status !== 'borrador' && $alquiler->status !== 'confirmado') {
            throw new \Exception('Solo se pueden anular alquileres en estado borrador o confirmado');
        }

        $alquiler->update(['status' => 'anulado']);

        return $alquiler;
    }

    public function registrarPago(int $alquilerId, float $amount, int $paymentMethodId): Alquiler
    {
        return DB::transaction(function () use ($alquilerId, $amount, $paymentMethodId) {
            $alquiler = Alquiler::findOrFail($alquilerId);

            $newPaid = $alquiler->amount_paid + $amount;
            $newDue = max(0, $alquiler->total - $newPaid);

            $paymentStatus = 'pendiente';
            if ($newDue <= 0) {
                $paymentStatus = 'pagado';
            } elseif ($newPaid > 0) {
                $paymentStatus = 'parcial';
            }

            $alquiler->update([
                'amount_paid' => $newPaid,
                'amount_due' => $newDue,
                'payment_status' => $paymentStatus,
                'payment_method_id' => $paymentMethodId,
            ]);

            return $alquiler;
        });
    }

    public function createAlquilerDirecto(array $data): Alquiler
    {
        return DB::transaction(function () use ($data) {
            $alquiler = Alquiler::create([
                'booking_id' => null,
                'ticket_tpv_id' => null,
                'alquiler_number' => null,
                'customer_id' => $data['customer_id'],
                'vehicle_id' => $data['vehicle_id'],
                'location_id' => $data['location_id'] ?? null,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'status' => $data['status'] ?? 'borrador',
                'payment_status' => $data['payment_status'] ?? 'pendiente',
                'subtotal' => $data['subtotal'] ?? 0,
                'descuento' => $data['descuento'] ?? 0,
                'base_imponible' => $data['base_imponible'] ?? 0,
                'iva' => $data['iva'] ?? 0,
                'total' => $data['total'] ?? 0,
                'amount_paid' => $data['amount_paid'] ?? 0,
                'amount_due' => $data['amount_due'] ?? ($data['total'] ?? 0),
                'deposit_amount' => $data['deposit_amount'] ?? 0,
                'payment_method_id' => $data['payment_method_id'] ?? null,
                'observaciones' => $data['observaciones'] ?? null,
                'fecha_entrega' => $data['fecha_entrega'] ?? now()->toDateString(),
                'fecha_devolucion' => $data['fecha_devolucion'] ?? now()->addDays(3)->toDateString(),
                'currency_code' => 'EUR',
                'user_id' => auth()->id(),
            ]);

            return $alquiler;
        });
    }

    public function generateInvoice(int $alquilerId): Invoice
    {
        return DB::transaction(function () use ($alquilerId) {
            $alquiler = Alquiler::findOrFail($alquilerId);

            $invoice = Invoice::create([
                'booking_id' => $alquiler->booking_id,
                'alquiler_id' => $alquiler->id,
                'tpv_ticket_id' => $alquiler->ticket_tpv_id,
                'customer_id' => $alquiler->customer_id,
                'invoice_number' => null,
                'type' => 'factura',
                'issue_date' => now(),
                'due_date' => now()->addDays(30),
                'subtotal' => $alquiler->base_imponible,
                'tax_amount' => $alquiler->iva,
                'total_amount' => $alquiler->total,
                'currency_code' => $alquiler->currency_code ?? 'EUR',
                'status' => 'borrador',
                'notes' => $alquiler->observaciones,
            ]);

            return $invoice;
        });
    }

    public function getAlquileresByPeriod(string $startDate, string $endDate): \Illuminate\Database\Eloquent\Collection
    {
        return Alquiler::with(['customer', 'vehicle', 'paymentMethod', 'user'])
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orderByDesc('start_date')
            ->get();
    }

    public function getAlquileresByStatus(string $status): \Illuminate\Database\Eloquent\Collection
    {
        return Alquiler::with(['customer', 'vehicle', 'paymentMethod', 'user'])
            ->where('status', $status)
            ->orderByDesc('created_at')
            ->get();
    }

    public function linkBooking(int $alquilerId, int $bookingId): Alquiler
    {
        $alquiler = Alquiler::findOrFail($alquilerId);
        $alquiler->update(['booking_id' => $bookingId]);

        return $alquiler;
    }
}
