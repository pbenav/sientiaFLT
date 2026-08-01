<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function processPayment(Booking $booking, string $method, array $data): Payment
    {
        return DB::transaction(function () use ($booking, $method, $data) {
            $transactionId = 'TXN-' . date('Ymd') . '-' . strtoupper(Str::random(8));

            if ($method === 'stripe') {
                $gatewayResponse = $this->processStripe($booking, $data);
            } elseif ($method === 'bizum') {
                $gatewayResponse = $this->processBizum($booking, $data);
            } elseif ($method === 'paypal') {
                $gatewayResponse = $this->processPaypal($booking, $data);
            } else {
                $gatewayResponse = ['status' => 'success', 'transaction_id' => $transactionId];
            }

            $payment = Payment::create([
                'booking_id' => $booking->id,
                'payment_method' => $method,
                'transaction_id' => $gatewayResponse['transaction_id'] ?? $transactionId,
                'amount' => $data['amount'] ?? $booking->amount_due,
                'currency_code' => $booking->currency_code,
                'status' => $gatewayResponse['status'] ?? 'pending',
                'gateway_response' => json_encode($gatewayResponse),
            ]);

            if (in_array($payment->status, ['success', 'completed'])) {
                $booking->increment('amount_paid', $payment->amount);
                $booking->update([
                    'amount_due' => $booking->total_amount - $booking->amount_paid,
                    'payment_status' => $booking->amount_due <= 0 ? 'paid' : 'partial',
                    'is_paid' => $booking->amount_due <= 0,
                ]);
            }

            return $payment;
        });
    }

    protected function processStripe(Booking $booking, array $data): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.stripe.secret'),
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->post('https://api.stripe.com/v1/charges', [
            'amount' => round(($data['amount'] ?? $booking->amount_due) * 100),
            'currency' => strtolower($booking->currency_code),
            'source' => $data['token'] ?? $data['payment_method_id'],
            'description' => 'Booking #' . $booking->booking_number,
        ]);

        return [
            'status' => $response->successful() ? 'success' : 'failed',
            'transaction_id' => $response->json('id') ?? '',
            'response' => $response->json(),
        ];
    }

    protected function processBizum(Booking $booking, array $data): array
    {
        Log::info('Bizum payment initiated', ['booking_id' => $booking->id]);

        return [
            'status' => 'pending',
            'transaction_id' => 'BZM-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'response' => ['message' => 'Bizum payment pending confirmation'],
        ];
    }

    protected function processPaypal(Booking $booking, array $data): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getPaypalToken(),
            'Content-Type' => 'application/json',
        ])->post('https://api-m.sandbox.paypal.com/v2/checkout/orders', [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'value' => ($data['amount'] ?? $booking->amount_due),
                    'currency_code' => $booking->currency_code,
                ],
            ]],
        ]);

        return [
            'status' => $response->successful() ? 'success' : 'failed',
            'transaction_id' => $response->json('id') ?? '',
            'response' => $response->json(),
        ];
    }

    protected function getPaypalToken(): string
    {
        $response = Http::withBasicAuth(
            config('services.paypal.client_id'),
            config('services.paypal.secret')
        )->post('https://api-m.sandbox.paypal.com/v1/oauth2/token', [
            'grant_type' => 'client_credentials',
        ]);

        return $response->json('access_token') ?? '';
    }
}
