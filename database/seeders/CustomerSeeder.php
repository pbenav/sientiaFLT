<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'first_name' => 'Juan',
                'last_name' => 'García Martínez',
                'nif_cif' => '12345678A',
                'email' => 'juan.garcia@email.com',
                'phone' => '+34 612 345 678',
                'address' => 'Calle Mayor 15, 2ºA',
                'postal_code' => '28013',
                'city' => 'Madrid',
                'province' => 'Madrid',
                'country' => 'ES',
                'notes' => 'Cliente habitual',
                'is_active' => true,
                'is_company' => false,
            ],
            [
                'first_name' => 'María',
                'last_name' => 'López Sánchez',
                'nif_cif' => '87654321B',
                'email' => 'maria.lopez@email.com',
                'phone' => '+34 698 765 432',
                'address' => 'Av. Diagonal 42, 3ºB',
                'postal_code' => '08006',
                'city' => 'Barcelona',
                'province' => 'Barcelona',
                'country' => 'ES',
                'notes' => null,
                'is_active' => true,
                'is_company' => false,
            ],
            [
                'first_name' => 'Carlos',
                'last_name' => 'Rodríguez Fernández',
                'nif_cif' => '11223344C',
                'email' => 'carlos.rodriguez@email.com',
                'phone' => '+34 655 123 789',
                'address' => 'Gran Vía 28, 5ºC',
                'postal_code' => '28013',
                'city' => 'Madrid',
                'province' => 'Madrid',
                'country' => 'ES',
                'notes' => 'Prefiere vehículos eléctricos',
                'is_active' => true,
                'is_company' => false,
            ],
            [
                'first_name' => 'Ana',
                'last_name' => 'Martín Ruiz',
                'nif_cif' => '55667788D',
                'email' => 'ana.martin@email.com',
                'phone' => '+34 677 890 123',
                'address' => 'Passeig de Gràcia 55, 1ºA',
                'postal_code' => '08007',
                'city' => 'Barcelona',
                'province' => 'Barcelona',
                'country' => 'ES',
                'notes' => null,
                'is_active' => true,
                'is_company' => false,
            ],
            [
                'first_name' => 'Pedro',
                'last_name' => 'Sánchez Torres',
                'nif_cif' => '99887766E',
                'email' => 'pedro.sanchez@email.com',
                'phone' => '+34 644 567 890',
                'address' => 'Calle Sierpes 12, 3ºD',
                'postal_code' => '41004',
                'city' => 'Sevilla',
                'province' => 'Sevilla',
                'country' => 'ES',
                'notes' => 'Cliente corporativo',
                'is_active' => true,
                'is_company' => false,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::firstOrCreate(
                ['nif_cif' => $customer['nif_cif']],
                $customer
            );
        }
    }
}
