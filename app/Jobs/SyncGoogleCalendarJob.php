<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Services\GoogleCalendarService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncGoogleCalendarJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function __construct(public readonly Booking $booking) {}

    public function handle(GoogleCalendarService $calendarService): void
    {
        if ($this->booking->status === 'cancelled') {
            $calendarService->removeBookingFromCalendar($this->booking->google_event_id ?? '');
        } else {
            $calendarService->addBookingToCalendar($this->booking);
        }
    }
}
