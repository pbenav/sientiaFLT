<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class BookingStatusService
{
    /**
     * Valid state transitions for bookings.
     */
    public const TRANSITIONS = [
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['active', 'cancelled'],
        'active' => ['completed'],
        'completed' => [],
        'cancelled' => [],
    ];

    public function canTransition(Booking $booking, string $from, string $to): bool
    {
        if (!isset(self::TRANSITIONS[$from])) {
            return false;
        }

        return in_array($to, self::TRANSITIONS[$from], true);
    }

    public function transition(Booking $booking, string $to, ?string $reason = null): Booking
    {
        $from = $booking->status;

        if (!$this->canTransition($booking, $from, $to)) {
            throw new \InvalidArgumentException(
                "Invalid transition from '{$from}' to '{$to}' for booking {$booking->booking_number}"
            );
        }

        return DB::transaction(function () use ($booking, $to, $from, $reason) {
            $booking->update([
                'status' => $to,
                'notes' => $booking->notes
                    ? $booking->notes . "\n[{$from} → {$to}]" . ($reason ? ": {$reason}" : '')
                    : "[{$from} → {$to}]" . ($reason ? ": {$reason}" : ''),
            ]);

            return $booking->refresh();
        });
    }

    public function confirm(Booking $booking, ?string $reason = null): Booking
    {
        return $this->transition($booking, 'confirmed', $reason);
    }

    public function activate(Booking $booking, ?string $reason = null): Booking
    {
        return $this->transition($booking, 'active', $reason);
    }

    public function complete(Booking $booking, ?string $reason = null): Booking
    {
        return $this->transition($booking, 'completed', $reason);
    }

    public function cancel(Booking $booking, ?string $reason = null): Booking
    {
        return $this->transition($booking, 'cancelled', $reason);
    }

    public function getValidTransitions(Booking $booking): array
    {
        return self::TRANSITIONS[$booking->status] ?? [];
    }

    public function getDisplayStatus(string $status): string
    {
        return match ($status) {
            'pending' => 'Pendiente',
            'confirmed' => 'Confirmada',
            'active' => 'Activa',
            'completed' => 'Completada',
            'cancelled' => 'Cancelada',
            'no_show' => 'No Show',
            default => $status,
        };
    }
}
