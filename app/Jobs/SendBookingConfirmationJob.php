<?php

namespace App\Jobs;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBookingConfirmationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public readonly Booking $booking) {}

    public function handle(): void
    {
        Mail::raw("Your booking #{$this->booking->booking_number} has been confirmed.", function ($message) {
            $message->to($this->booking->customer->email)
                ->subject('Booking Confirmation - ' . $this->booking->booking_number);
        });
    }
}
