<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tax::firstOrCreate(
            ['name' => 'IVA 21%'],
            [
                'rate' => 21.00,
                'is_active' => true,
                'is_default' => true,
                'country_code' => 'ES',
                'description' => 'Impuesto sobre el Valor Añadido (General)',
            ]
        );

        Tax::firstOrCreate(
            ['name' => 'IVA 10%'],
            [
                'rate' => 10.00,
                'is_active' => true,
                'is_default' => false,
                'country_code' => 'ES',
                'description' => 'Impuesto sobre el Valor Añadido (Reducido)',
            ]
        );

        Tax::firstOrCreate(
            ['name' => 'IGIC 7%'],
            [
                'rate' => 7.00,
                'is_active' => true,
                'is_default' => false,
                'country_code' => 'ES',
                'description' => 'Impuesto General Indirecto Canario',
            ]
        );
        
        Tax::firstOrCreate(
            ['name' => 'Exento'],
            [
                'rate' => 0.00,
                'is_active' => true,
                'is_default' => false,
                'country_code' => 'ES',
                'description' => 'Exento de impuestos',
            ]
        );
    }
}
