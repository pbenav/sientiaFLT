<?php

namespace Database\Factories;

use App\Models\TicketTPV;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketTPVLinea>
 */
class TicketTPVLineaFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $vehicle = Vehicle::inRandomOrder()->first();
        $quantity = $this->faker->numberBetween(1, 3);
        $unitPrice = $vehicle ? $vehicle->price_per_day ?? $this->faker->randomFloat(2, 15, 150) : $this->faker->randomFloat(2, 15, 150);
        $discount = $this->faker->randomElement([0, 0, 0, 5, 10, 15]);

        $grossSubtotal = $unitPrice * $quantity;
        $subtotal = round($grossSubtotal * (1 - $discount / 100), 2);
        $taxRate = 21;
        $taxAmount = round($subtotal * ($taxRate / 100), 2);
        $total = round($subtotal + $taxAmount, 2);

        return [
            'ticket_tpv_id' => TicketTPV::factory(),
            'vehicle_id' => $vehicle?->id,
            'vehicle_name' => $vehicle?->name ?? $this->faker->word(),
            'category_name' => $vehicle?->category?->name ?? $this->faker->word(),
            'description' => $vehicle?->description ?? $this->faker->sentence(3),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'discount_percentage' => $discount,
            'tax_rate' => $taxRate,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total' => $total,
        ];
    }
}
