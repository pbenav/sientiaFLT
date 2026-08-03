<?php

namespace Tests\Unit\Services;

use App\DTOs\VehicleSearchDto;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Services\VehicleSearchService;
use Tests\TestCase;

class VehicleSearchServiceTest extends TestCase
{
    protected VehicleSearchService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new VehicleSearchService(
            new \App\Services\AvailabilityService()
        );
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
            'name' => 'Test Vehicle ' . uniqid(),
            'category_id' => $category->id,
            'status' => 'active',
            'visibility' => 'public',
            'daily_rate' => 50.00,
            'seats' => 5,
            'type' => 'Sedan',
            'location_id' => null,
        ], $overrides));
    }

    /** @test */
    public function it_searches_by_category()
    {
        $category = $this->createCategory('SUVs');

        $vehicle = $this->createVehicle($category, [
            'name' => 'Test SUV ' . uniqid(),
            'daily_rate' => 80.00,
            'seats' => 7,
            'type' => 'SUV',
        ]);

        $dto = VehicleSearchDto::fromRequest([
            'category_id' => $category->id,
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-05',
        ]);

        $results = $this->service->search($dto);

        $this->assertTrue($results->contains($vehicle));
    }

    /** @test */
    public function it_searches_by_type()
    {
        $category = $this->createCategory('Sedans');

        $vehicle = $this->createVehicle($category, [
            'name' => 'Test Sedan ' . uniqid(),
            'daily_rate' => 50.00,
            'type' => 'Sedan',
        ]);

        $dto = VehicleSearchDto::fromRequest([
            'type' => 'Sedan',
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-05',
        ]);

        $results = $this->service->search($dto);

        $this->assertTrue($results->isNotEmpty());
    }

    /** @test */
    public function it_sorts_by_price_asc()
    {
        $category = $this->createCategory('SortAsc');

        $cheap = $this->createVehicle($category, [
            'name' => 'Cheap Car ' . uniqid(),
            'daily_rate' => 30.00,
            'seats' => 5,
            'type' => 'Hatchback',
        ]);

        $expensive = $this->createVehicle($category, [
            'name' => 'Expensive Car ' . uniqid(),
            'daily_rate' => 120.00,
            'seats' => 5,
            'type' => 'Luxury',
        ]);

        $dto = VehicleSearchDto::fromRequest([
            'category_id' => $category->id,
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-05',
        ]);

        $results = $this->service->searchWithSort($dto, 'price_asc');

        $this->assertEquals(2, $results->count());
        $this->assertEquals($expensive->id, $results->first()->id);
        $this->assertEquals($cheap->id, $results->last()->id);
    }

    /** @test */
    public function it_sorts_by_price_desc()
    {
        $category = $this->createCategory('SortDesc');

        $cheap = $this->createVehicle($category, [
            'name' => 'Cheap Car 2 ' . uniqid(),
            'daily_rate' => 30.00,
            'seats' => 5,
            'type' => 'Hatchback',
        ]);

        $expensive = $this->createVehicle($category, [
            'name' => 'Expensive Car 2 ' . uniqid(),
            'daily_rate' => 120.00,
            'seats' => 5,
            'type' => 'Luxury',
        ]);

        $dto = VehicleSearchDto::fromRequest([
            'category_id' => $category->id,
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-05',
        ]);

        $results = $this->service->searchWithSort($dto, 'price_desc');

        $this->assertEquals(2, $results->count());
        $this->assertEquals($expensive->id, $results->first()->id);
        $this->assertEquals($cheap->id, $results->last()->id);
    }

    /** @test */
    public function it_filters_by_price_range()
    {
        $category = $this->createCategory('PriceTest');

        $this->createVehicle($category, [
            'name' => 'Cheap ' . uniqid(),
            'daily_rate' => 20.00,
            'seats' => 5,
            'type' => 'Hatchback',
        ]);

        $this->createVehicle($category, [
            'name' => 'Expensive ' . uniqid(),
            'daily_rate' => 200.00,
            'seats' => 5,
            'type' => 'Luxury',
        ]);

        $dto = VehicleSearchDto::fromRequest([
            'min_price' => 50,
            'max_price' => 100,
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-05',
        ]);

        $results = $this->service->search($dto);

        $this->assertFalse($results->contains(fn($v) => $v->daily_rate < 50));
        $this->assertFalse($results->contains(fn($v) => $v->daily_rate > 100));
    }
}
