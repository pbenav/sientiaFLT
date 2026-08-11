<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\FormaPago;
use App\Models\TicketTPV;
use App\Models\TicketTPVLinea;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TPVService
{
    public function createTicket(array $data): TicketTPV
    {
        return DB::transaction(function () use ($data) {
            $ticket = TicketTPV::create([
                'user_id' => $data['user_id'] ?? auth()->id(),
                'customer_id' => $data['customer_id'] ?? null,
                'session_id' => (string) Str::uuid(),
                'status' => 'open',
                'notes' => $data['notes'] ?? null,
            ]);

            return $ticket;
        });
    }

    public function addLinea(int $ticketId, array $data): TicketTPVLinea
    {
        return DB::transaction(function () use ($ticketId, $data) {
            $ticket = TicketTPV::findOrFail($ticketId);

            if ($ticket->status !== 'open') {
                throw new \Exception('El ticket no está abierto');
            }

            $linea = TicketTPVLinea::create([
                'ticket_tpv_id' => $ticketId,
                'vehicle_id' => $data['vehicle_id'] ?? null,
                'vehicle_name' => $data['vehicle_name'] ?? null,
                'category_name' => $data['category_name'] ?? null,
                'description' => $data['description'] ?? null,
                'quantity' => $data['quantity'] ?? 1,
                'unit_price' => $data['unit_price'],
                'discount_percentage' => $data['discount_percentage'] ?? 0,
                'tax_rate' => $data['tax_rate'] ?? 21,
            ]);

            $ticket->recalculateTotals();

            return $linea;
        });
    }

    public function removeLinea(int $lineaId): bool
    {
        $linea = TicketTPVLinea::findOrFail($lineaId);
        $ticketId = $linea->ticket_tpv_id;

        $linea->delete();

        $ticket = TicketTPV::find($ticketId);
        if ($ticket) {
            $ticket->recalculateTotals();
        }

        return true;
    }

    public function completeTicket(int $ticketId, array $paymentData): TicketTPV
    {
        return DB::transaction(function () use ($ticketId, $paymentData) {
            $ticket = TicketTPV::findOrFail($ticketId);

            if ($ticket->status !== 'open') {
                throw new \Exception('El ticket no está abierto');
            }

            $ticket->recalculateTotals();

            $amountPaid = $paymentData['amount_paid'] ?? $ticket->total;
            $paymentMethod = $paymentData['payment_method'] ?? 'cash';

            $ticket->complete($paymentMethod, $amountPaid);

            return $ticket;
        });
    }

    public function cancelTicket(int $ticketId): TicketTPV
    {
        $ticket = TicketTPV::findOrFail($ticketId);
        $ticket->cancel();

        return $ticket;
    }

    public function getOpenTickets(): \Illuminate\Database\Eloquent\Collection
    {
        return TicketTPV::with(['customer', 'user'])
            ->open()
            ->orderByDesc('created_at')
            ->get();
    }

    public function getTicketStats(): array
    {
        $today = now()->startOfDay();

        return [
            'tickets_today' => TicketTPV::whereDate('created_at', $today)->count(),
            'total_today' => TicketTPV::whereDate('created_at', $today)
                ->where('status', 'completed')
                ->sum('total'),
            'active_tickets' => TicketTPV::open()->count(),
            'pending_payments' => TicketTPV::where('status', 'completed')
                ->where('amount_due', '>', 0)
                ->count(),
        ];
    }

    public function getAvailableVehicles(): \Illuminate\Database\Eloquent\Collection
    {
        return Vehicle::with(['category', 'bookings'])
            ->where('is_active', true)
            ->whereDoesntHave('bookings', function ($query) {
                $query->where('status', 'activo')
                    ->orWhere('status', 'confirmado');
            })
            ->get();
    }

    public function searchCustomers(string $query): \Illuminate\Database\Eloquent\Collection
    {
        return Customer::where(function ($q) use ($query) {
            $q->where('first_name', 'like', "%{$query}%")
                ->orWhere('last_name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('phone', 'like', "%{$query}%")
                ->orWhere('nif_cif', 'like', "%{$query}%");
        })
            ->limit(20)
            ->get();
    }

    public function getPaymentMethods(): \Illuminate\Database\Eloquent\Collection
    {
        return FormaPago::where('activo', true)->get();
    }

    public function getTicketWithLines(int $ticketId): ?TicketTPV
    {
        return TicketTPV::with(['lineas', 'customer', 'user'])
            ->find($ticketId);
    }
}
