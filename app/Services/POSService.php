<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\FormaPago;
use App\Models\Invoice;
use App\Services\PricingService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class POSService
{
    protected PricingService $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    public function createSession(): string
    {
        return 'POS-SESS-' . strtoupper(Str::random(8));
    }

    public function addVehicleToSession(string $sessionId, int $vehicleId, ?int $customerId, int $userId): ?Booking
    {
        $vehicle = Vehicle::find($vehicleId);
        if (!$vehicle) return null;

        $booking = Booking::create([
            'booking_number' => 'POS-' . strtoupper(Str::random(8)),
            'pos_session_id' => $sessionId,
            'user_id' => $userId,
            'customer_id' => $customerId,
            'vehicle_id' => $vehicle->id,
            'booking_source' => 'pos',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'start_date' => now(),
            'end_date' => now()->addDay(),
            'currency_code' => 'EUR',
        ]);

        $this->recalculateBooking($booking);
        return $booking;
    }

    public function updateBookingDates(Booking $booking, string $startDate, string $endDate): Booking
    {
        $booking->start_date = Carbon::parse($startDate);
        $booking->end_date = Carbon::parse($endDate);
        
        // Ensure end date is strictly after start date
        if ($booking->end_date->lessThanOrEqualTo($booking->start_date)) {
            $booking->end_date = $booking->start_date->copy()->addDay();
        }

        $booking->save();
        $this->recalculateBooking($booking);
        return $booking;
    }

    public function removeBooking(string $sessionId, int $bookingId): bool
    {
        $booking = Booking::where('pos_session_id', $sessionId)->find($bookingId);
        if ($booking) {
            return $booking->delete();
        }
        return false;
    }

    public function updateCustomerForSession(string $sessionId, ?int $customerId): void
    {
        Booking::where('pos_session_id', $sessionId)->update(['customer_id' => $customerId]);
    }

    public function cancelSession(string $sessionId): void
    {
        Booking::where('pos_session_id', $sessionId)->delete();
    }

    public function checkoutSession(string $sessionId, string $paymentMethod, float $amountPaid): bool
    {
        $bookings = Booking::where('pos_session_id', $sessionId)->get();
        if ($bookings->isEmpty()) return false;

        $paymentMethodObj = FormaPago::where('codigo', strtolower($paymentMethod))->first() 
                            ?? FormaPago::where('activo', true)->first();
        $paymentMethodId = $paymentMethodObj ? $paymentMethodObj->id : null;

        DB::transaction(function () use ($bookings, $paymentMethodId) {
            foreach ($bookings as $booking) {
                $booking->status = 'active'; 
                $booking->payment_status = 'paid';
                $booking->fecha_entrega = now();
                $booking->amount_paid = $booking->total_amount;
                $booking->amount_due = 0;
                $booking->payment_method_id = $paymentMethodId;
                $booking->save();

                $year = now()->format('Y');
                $lastInvoice = Invoice::where('invoice_number', 'like', "F-{$year}-%")->orderBy('invoice_number', 'desc')->first();
                $nextSequence = $lastInvoice ? intval(substr($lastInvoice->invoice_number, -5)) + 1 : 1;
                $invoiceNumber = sprintf("F-%s-%05d", $year, $nextSequence);

                $invoice = Invoice::create([
                    'booking_id' => $booking->id,
                    'customer_id' => $booking->customer_id,
                    'invoice_number' => $invoiceNumber,
                    'type' => 'factura',
                    'issue_date' => now(),
                    'due_date' => now(),
                    'subtotal' => $booking->subtotal,
                    'tax_amount' => $booking->tax_amount,
                    'total_amount' => $booking->total_amount,
                    'currency_code' => $booking->currency_code,
                    'status' => 'paid',
                ]);
            
                // Dispatch automatic background signing (VeriFactu / FacturaE)
                \App\Jobs\SignAndSendInvoiceJob::dispatch($invoice);
            }
        });

        return true;
    }

    public function getPendingSessions(): array
    {
        $bookings = Booking::with(['customer'])
            ->where('booking_source', 'pos')
            ->where('status', 'pending')
            ->whereNotNull('pos_session_id')
            ->orderByDesc('created_at')
            ->get();
            
        return $bookings->groupBy('pos_session_id')->map(function ($group) {
            $first = $group->first();
            return [
                'session_id' => $first->pos_session_id,
                'customer' => $first->customer,
                'items_count' => $group->count(),
                'total_amount' => $group->sum('total_amount'),
                'created_at' => $first->created_at,
            ];
        })->values()->toArray();
    }

    protected function recalculateBooking(Booking $booking): void
    {
        if (!$booking->vehicle) return;
        
        $details = $this->pricingService->calculatePrice($booking->vehicle, $booking->start_date, $booking->end_date, []);
        
        $booking->subtotal = $details['subtotal'];
        $booking->discount_amount = $details['discount'];
        $booking->tax_amount = $details['tax_amount'];
        $booking->total_amount = $details['total'];
        $booking->save();
    }
}
