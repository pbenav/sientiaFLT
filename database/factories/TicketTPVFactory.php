<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketTPV>
 */
class TicketTPVFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['open', 'completed', 'cancelled']);
        $paymentMethod = $this->faker->randomElement(['cash', 'card', 'mixed']);
        $total = $this->faker->randomFloat(2, 20, 500);
        $amountPaid = $status === 'completed' ? $total : null;

        return [
            'user_id' => User::factory(),
            'customer_id' => Customer::factory(),
            'session_id' => (string) Str::uuid(),
            'numero' => 'TPV-' . date('Y') . '-' . str_pad($this->faker->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'status' => $status,
            'subtotal' => $total * 0.84,
            'iva_total' => $total * 0.16,
            'total' => $total,
            'descuento_porcentaje' => 0,
            'descuento_importe' => 0,
            'payment_method' => $status === 'completed' ? $paymentMethod : null,
            'amount_paid' => $amountPaid,
            'change_given' => $amountPaid && $amountPaid > $total ? round($amountPaid - $total, 2) : null,
            'amount_due' => $status === 'completed' ? 0 : $total,
            'completed_at' => $status === 'completed' ? $this->faker->dateTimeBetween('-30 days') : null,
            'notes' => $this->faker->sentence(),
        ];
    }
}
