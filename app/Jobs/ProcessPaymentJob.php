<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(
        public readonly Booking $booking,
        public readonly string $paymentMethod,
        public readonly array $paymentData
    ) {}

    public function handle(PaymentService $paymentService): void
    {
        $payment = $paymentService->processPayment(
            $this->booking,
            $this->paymentMethod,
            $this->paymentData
        );

        if ($payment->status === 'completed') {
            $this->booking->update([
                'payment_status' => 'paid',
                'is_paid' => true,
            ]);
        }
    }
}
