<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\FormaPago;
use App\Models\Location;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alquiler>
 */
class AlquilerFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $vehicle = Vehicle::inRandomOrder()->first();
        $customer = Customer::factory()->create();
        $startDate = $this->faker->dateTimeBetween('+1 day', '+30 days');
        $endDate = (clone $startDate)->addDays($this->faker->numberBetween(1, 14));
        $status = $this->faker->randomElement(['borrador', 'confirmado', 'activo', 'completado', 'anulado']);
        $paymentStatus = $this->faker->randomElement(['pendiente', 'parcial', 'pagado']);

        $subtotal = $this->faker->randomFloat(2, 50, 800);
        $descuento = $subtotal * $this->faker->randomFloat(2, 0, 0.2);
        $baseImponible = $subtotal - $descuento;
        $iva = $baseImponible * 0.21;
        $total = $baseImponible + $iva;

        return [
            'booking_id' => null,
            'ticket_tpv_id' => null,
            'alquiler_number' => 'ALQ-' . date('Y') . '-' . str_pad($this->faker->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle?->id ?? 1,
            'location_id' => Location::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status,
            'payment_status' => $paymentStatus,
            'subtotal' => $subtotal,
            'descuento' => $descuento,
            'base_imponible' => $baseImponible,
            'iva' => $iva,
            'total' => $total,
            'amount_paid' => $paymentStatus === 'pagado' ? $total : ($paymentStatus === 'parcial' ? $total * 0.5 : 0),
            'amount_due' => $total - ($paymentStatus === 'pagado' ? $total : ($paymentStatus === 'parcial' ? $total * 0.5 : 0)),
            'deposit_amount' => $this->faker->randomFloat(2, 0, 100),
            'payment_method_id' => FormaPago::inRandomOrder()->first()?->id,
            'observaciones' => $this->faker->sentence(),
            'observaciones_internas' => $this->faker->optional()->sentence(),
            'fecha_entrega' => $startDate->format('Y-m-d'),
            'fecha_devolucion' => $endDate->format('Y-m-d'),
            'start_location' => $this->faker->optional()->city(),
            'end_location' => $this->faker->optional()->city(),
            'return_location' => $this->faker->optional()->city(),
            'currency_code' => 'EUR',
            'user_id' => User::factory(),
        ];
    }
}
