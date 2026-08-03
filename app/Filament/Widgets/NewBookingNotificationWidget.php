<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\Widget;

class NewBookingNotificationWidget extends Widget
{
    protected static string $view = 'filament.widgets.new-booking-notification';

    protected static ?int $sort = 0;

    public $newBookings = [];

    public $count = 0;

    public function mount(): void
    {
        $this->loadNewBookings();
    }

    public function refreshBookings(): void
    {
        $this->loadNewBookings();
    }

    public function dismissAll(): void
    {
        // Store dismissed state in session
        session()->put('dismissed_new_booking_' . auth()->id(), now()->timestamp);
    }

    protected function loadNewBookings(): void
    {
        $lastDismissed = session('dismissed_new_booking_' . auth()->id(), 0);
        $dismissedTime = \Carbon\Carbon::createFromTimestamp($lastDismissed);

        $this->newBookings = Booking::with(['customer', 'vehicle'])
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subHours(24))
            ->where('created_at', '>', $dismissedTime)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->toArray();

        $this->count = count($this->newBookings);
    }

    public function getViewData(): array
    {
        return [
            'newBookings' => $this->newBookings,
            'count' => $this->count,
        ];
    }
}
