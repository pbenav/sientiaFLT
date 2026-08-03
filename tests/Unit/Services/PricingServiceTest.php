<?php

namespace Tests\Unit\Services;

use App\Enums\BookingStatus;
use App\Models\CategoryVolumeDiscount;
use App\Models\PriceRule;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Services\PricingService;
use Carbon\Carbon;
use Tests\TestCase;

class PricingServiceTest extends TestCase
{
    protected PricingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PricingService();
    }

    protected function createCategory(string $name): VehicleCategory
    {
        $uniqueName = $name . ' ' . uniqid();
        $slug = strtolower(str_replace(' ', '-', $uniqueName));
        return VehicleCategory::create([
            'name' => $uniqueName,
            'slug' => $slug,
            'description' => $name,
        ]);
    }

    protected function createVehicle(VehicleCategory $category, array $overrides = []): Vehicle
    {
        return Vehicle::create(array_merge([
            'name' => 'Test Vehicle ' . $category->id,
            'category_id' => $category->id,
            'status' => 'active',
            'visibility' => 'public',
            'daily_rate' => 50.00,
            'seats' => 5,
            'type' => 'Sedan',
            'location_id' => null,
            'is_active' => true,
            'is_available' => true,
        ], $overrides));
    }

    /** @test */
    public function it_calculates_base_price()
    {
        $category = $this->createCategory('Base Price Test');

        $vehicle = $this->createVehicle($category, ['daily_rate' => 50.00]);

        $startDate = Carbon::parse('2026-01-01');
        $endDate = Carbon::parse('2026-01-03'); // 2 days

        $result = $this->service->calculatePrice($vehicle, $startDate, $endDate);

        $this->assertEquals(100.00, $result['subtotal']);
        $this->assertEquals(2, $result['days']);
    }

    /** @test */
    public function it_applies_price_rules()
    {
        $category = $this->createCategory('Price Rules Test');

        $vehicle = $this->createVehicle($category, ['daily_rate' => 50.00]);

        PriceRule::create([
            'vehicle_id' => $vehicle->id,
            'rule_type' => 'discount',
            'min_days' => 7,
            'max_days' => 30,
            'discount_percentage' => 15.00,
        ]);

        $startDate = Carbon::parse('2026-01-01');
        $endDate = Carbon::parse('2026-01-10'); // 9 days

        $result = $this->service->calculatePrice($vehicle, $startDate, $endDate);

        $this->assertEquals(9, $result['days']);
        $this->assertLessThan(450.00, $result['subtotal']); // 450 - 15% = 382.50
    }

    /** @test */
    public function it_applies_volume_discounts()
    {
        $category = $this->createCategory('Volume Discount Test');

        $vehicle = $this->createVehicle($category, ['daily_rate' => 50.00]);

        CategoryVolumeDiscount::create([
            'vehicle_category_id' => $category->id,
            'min_days' => 7,
            'discount_percent' => 10.00,
        ]);

        $startDate = Carbon::parse('2026-01-01');
        $endDate = Carbon::parse('2026-01-10'); // 9 days

        $result = $this->service->calculatePrice($vehicle, $startDate, $endDate);

        $this->assertEquals(9, $result['days']);
        $this->assertArrayHasKey('discount', $result);
        $this->assertGreaterThan(0, $result['discount']);
    }

    /** @test */
    public function it_calculates_tax_amount()
    {
        $category = $this->createCategory('Tax Test');

        $vehicle = $this->createVehicle($category, ['daily_rate' => 100.00]);

        $startDate = Carbon::parse('2026-01-01');
        $endDate = Carbon::parse('2026-01-02'); // 1 day

        $result = $this->service->calculatePrice($vehicle, $startDate, $endDate);

        $this->assertEquals(50.00, $result['base_price']);
        $this->assertGreaterThan(0, $result['tax_amount']);
        $this->assertEquals($result['subtotal'] + $result['tax_amount'] + $result['extras_total'], round($result['total'], 2));
    }

    /** @test */
    public function it_handles_same_day_booking()
    {
        $category = $this->createCategory('Same Day Test');

        $vehicle = $this->createVehicle($category, ['daily_rate' => 50.00]);

        $startDate = Carbon::parse('2026-01-01 10:00');
        $endDate = Carbon::parse('2026-01-01 18:00');

        $result = $this->service->calculatePrice($vehicle, $startDate, $endDate);

        $this->assertEquals(1, $result['days']);
    }
}
