<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FormaPago>
 */
class FormaPagoFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $tipos = ['contado', 'transferencia', 'tarjeta', 'pagare', 'recibo_bancario', 'efectivo'];

        return [
            'codigo' => strtoupper($this->faker->unique()->lexify('form???')),
            'nombre' => $this->faker->randomElement(['Efectivo', 'Tarjeta de Crédito', 'Transferencia Bancaria', 'PayPal', 'PayPal', 'PayPal', 'PayPal']),
            'tipo' => $this->faker->randomElement($tipos),
            'activo' => true,
            'descripcion' => $this->faker->sentence(),
        ];
    }
}
