<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use App\Models\VehicleCategory;
use App\Models\Location;
use App\Models\PricePeriod;
use App\Models\CategoryVolumeDiscount;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create Ibiza Puerto location
        $location = Location::firstOrCreate(
            ['name' => 'Puerto de Ibiza'],
            [
                'address' => 'Avinguda de Santa Eulària des Riu, 25',
                'city' => 'Puerto de Ibiza',
                'postal_code' => '07800',
                'country' => 'España',
                'phone' => '+34 971 000 000',
                'email' => 'info@extrarent.com',
                'is_active' => true,
            ]
        );

        // Create vehicle categories
        $scooterCategory = VehicleCategory::firstOrCreate(
            ['slug' => 'scooters-125cc'],
            [
                'name' => 'Scooters 125cc',
                'description' => 'Motos de 125cc: SYM, Piaggio, Vespa y similares',
                'is_active' => true,
            ]
        );

        // Create price periods with date ranges
        $periods = [
            [
                'name' => 'Bajo (Enero-Mayo)',
                'start_date' => '2026-01-01',
                'end_date' => '2026-05-31',
                'base_price' => 45.00,
                'active' => true,
            ],
            [
                'name' => 'Medio (Junio, Septiembre)',
                'start_date' => '2026-06-01',
                'end_date' => '2026-06-30',
                'base_price' => 55.00,
                'active' => true,
            ],
            [
                'name' => 'Medio (Septiembre)',
                'start_date' => '2026-09-01',
                'end_date' => '2026-09-30',
                'base_price' => 55.00,
                'active' => true,
            ],
            [
                'name' => 'Alto (Julio-Agosto)',
                'start_date' => '2026-07-01',
                'end_date' => '2026-08-31',
                'base_price' => 70.00,
                'active' => true,
            ],
            [
                'name' => 'Festivos (Navidad)',
                'start_date' => '2026-12-20',
                'end_date' => '2027-01-06',
                'base_price' => 80.00,
                'active' => true,
            ],
        ];

        foreach ($periods as $period) {
            PricePeriod::firstOrCreate(
                ['name' => $period['name']],
                $period
            );
        }

        // Attach price periods to scooter category
        $pricePeriods = PricePeriod::all();
        $scooterCategory->pricePeriods()->sync($pricePeriods->pluck('id')->toArray());

        // Create volume discounts for scooter category
        $discounts = [
            ['min_days' => 1, 'max_days' => 6, 'discount_percent' => 0],
            ['min_days' => 7, 'max_days' => 13, 'discount_percent' => 10],
            ['min_days' => 14, 'max_days' => null, 'discount_percent' => 15],
        ];

        foreach ($discounts as $discount) {
            CategoryVolumeDiscount::firstOrCreate(
                [
                    'vehicle_category_id' => $scooterCategory->id,
                    'min_days' => $discount['min_days'],
                    'max_days' => $discount['max_days'],
                ],
                $discount
            );
        }

        // Clear existing scooter vehicles
        \DB::table('vehicle_images')->where('path', 'like', '%symphony%')->delete();
        \DB::table('vehicle_images')->where('path', 'like', '%medley%')->delete();
        \DB::table('vehicle_images')->where('path', 'like', '%vespa%')->delete();
        Vehicle::where('slug', 'like', '%symphony%')->delete();
        Vehicle::where('slug', 'like', '%medley%')->delete();
        Vehicle::where('slug', 'like', '%primavera%')->delete();

        // ============================================================
        // SCOOTER 1: SYM Symphony 125cc
        // ============================================================
        $sym = Vehicle::create([
            'location_id' => $location->id,
            'category_id' => $scooterCategory->id,
            'sku' => 'SYM-SYM-125',
            'name' => 'SYM Symphony 125cc',
            'slug' => 'sym-symphony-125cc',
            'brand' => 'SYM',
            'model' => 'Symphony 125',
            'year' => '2024',
            'type' => 'scooter',
            'body_type' => 'Scooter',
            'fuel_type' => 'gasoline',
            'transmission' => 'automatic',
            'engine' => '125cc',
            'power_hp' => '11',
            'seats' => 2,
            'doors' => 0,
            'luggage_large' => 0,
            'luggage_small' => 1,
            'automatic_gears' => 1,
            'color' => 'Varios',
            'emission_code' => 'Euro 5',
            'energy_type' => 'Gasolina',
            'transmission_type' => 'Variomatic',
            'drive_type' => 'Trasera',
            'gearbox' => 'CVT',
            'description' => 'El SYM Symphony 125cc es el scooter perfecto para recorrer Ibiza con comodidad y estilo. Ideal para parejas gracias a su amplio asiento y excelente estabilidad. Equipado con motor de última generación Euro 5, gran maletero bajo asiento y consumo reducido.',
            'features' => json_encode([
                'Motor 125cc 4 tiempos',
                'Euro 5',
                'Arranque eléctrico',
                'Maletero bajo asiento 20L',
                'Frenos disco delanteros',
                'Asiento amplio para 2',
                'Gran estabilidad',
                'Consumo: 2.5L/100km',
            ]),
            'daily_rate' => 60.00,
            'weekly_rate' => 320.00,
            'monthly_rate' => 800.00,
            'security_deposit' => 200.00,
            'is_active' => true,
            'is_available' => true,
            'show_on_homepage' => true,
            'show_on_fleet' => true,
            'is_featured' => true,
            'is_recommended' => true,
            'erp_sync_status' => 'synced',
        ]);

        VehicleImage::create([
            'vehicle_id' => $sym->id,
            'path' => 'images/vehicles/sym-symphony-125.jpg',
            'url' => '/images/vehicles/sym-symphony-125.jpg',
            'alt_text' => 'SYM Symphony 125cc - Alquiler motos Ibiza',
            'sort_order' => 1,
        ]);

        // ============================================================
        // SCOOTER 2: Piaggio Medley 125cc
        // ============================================================
        $piaggio = Vehicle::create([
            'location_id' => $location->id,
            'category_id' => $scooterCategory->id,
            'sku' => 'PIAGGIO-MEDLEY-125',
            'name' => 'Piaggio Medley 125cc',
            'slug' => 'piaggio-medley-125cc',
            'brand' => 'Piaggio',
            'model' => 'Medley',
            'year' => '2024',
            'type' => 'scooter',
            'body_type' => 'Scooter',
            'fuel_type' => 'gasoline',
            'transmission' => 'automatic',
            'engine' => '125cc',
            'power_hp' => '12',
            'seats' => 2,
            'doors' => 0,
            'luggage_large' => 0,
            'luggage_small' => 1,
            'automatic_gears' => 1,
            'color' => 'Varios',
            'emission_code' => 'Euro 5',
            'energy_type' => 'Gasolina',
            'transmission_type' => 'Variomatic',
            'drive_type' => 'Trasera',
            'gearbox' => 'CVT',
            'description' => 'El Piaggio Medley 125cc combina el estilo clásico italiano con la tecnología más moderna. Perfecto para quienes buscan un scooter elegante, cómodo y con gran capacidad de almacenamiento. Ideal para recorrer las playas y calles de Ibiza con total libertad.',
            'features' => json_encode([
                'Motor 125cc 4 tiempos',
                'Euro 5',
                'Arranque eléctrico',
                'Maletero bajo asiento 27L',
                'USB integrado',
                'Frenos ABS',
                'Conectividad Bluetooth',
                'Control de tracción',
            ]),
            'daily_rate' => 70.00,
            'weekly_rate' => 380.00,
            'monthly_rate' => 950.00,
            'security_deposit' => 200.00,
            'is_active' => true,
            'is_available' => true,
            'show_on_homepage' => true,
            'show_on_fleet' => true,
            'is_featured' => true,
            'is_recommended' => false,
            'erp_sync_status' => 'synced',
        ]);

        VehicleImage::create([
            'vehicle_id' => $piaggio->id,
            'path' => 'images/vehicles/medley-125.jpg',
            'url' => '/images/vehicles/medley-125.jpg',
            'alt_text' => 'Piaggio Medley 125cc - Alquiler motos Ibiza',
            'sort_order' => 1,
        ]);

        // ============================================================
        // SCOOTER 3: Vespa Primavera 125
        // ============================================================
        $vespa = Vehicle::create([
            'location_id' => $location->id,
            'category_id' => $scooterCategory->id,
            'sku' => 'VESPA-PRIMAVERA-125',
            'name' => 'Vespa Primavera 125',
            'slug' => 'vespa-primavera-125',
            'brand' => 'Vespa',
            'model' => 'Primavera',
            'year' => '2024',
            'type' => 'scooter',
            'body_type' => 'Scooter',
            'fuel_type' => 'gasoline',
            'transmission' => 'automatic',
            'engine' => '125cc',
            'power_hp' => '11',
            'seats' => 2,
            'doors' => 0,
            'luggage_large' => 0,
            'luggage_small' => 1,
            'automatic_gears' => 1,
            'color' => 'Varios',
            'emission_code' => 'Euro 5',
            'energy_type' => 'Gasolina',
            'transmission_type' => 'Variomatic',
            'drive_type' => 'Trasera',
            'gearbox' => 'CVT',
            'description' => 'La Vespa Primavera 125 es el icono del estilo italiano. Con su diseño atemporal, chasis de acero y acabados premium, es la elección perfecta para quienes quieren recorrer Ibiza con clase. Motor eficiente, confort excepcional y la esencia Vespa que no pasa de moda.',
            'features' => json_encode([
                'Motor 125cc 4 tiempos T4',
                'Euro 5',
                'Arranque eléctrico',
                'Maletero bajo asiento 17L',
                'Panel digital completo',
                'Frenos disco delanteros',
                'Dirección ultra ligera',
                'Diseño italiano clásico',
            ]),
            'daily_rate' => 70.00,
            'weekly_rate' => 380.00,
            'monthly_rate' => 950.00,
            'security_deposit' => 200.00,
            'is_active' => true,
            'is_available' => true,
            'show_on_homepage' => true,
            'show_on_fleet' => true,
            'is_featured' => true,
            'is_recommended' => true,
            'erp_sync_status' => 'synced',
        ]);

        VehicleImage::create([
            'vehicle_id' => $vespa->id,
            'path' => 'images/vehicles/vespa-primavera-125.jpg',
            'url' => '/images/vehicles/vespa-primavera-125.jpg',
            'alt_text' => 'Vespa Primavera 125 - Alquiler motos Ibiza',
            'sort_order' => 1,
        ]);

        $this->command->info('Vehicle categories, price periods, volume discounts and 3 scooter vehicles created successfully!');
    }
}
