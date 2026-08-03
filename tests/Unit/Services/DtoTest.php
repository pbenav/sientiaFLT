<?php

namespace Tests\Unit\Services;

use App\DTOs\BookingCreateDto;
use App\DTOs\PricingRequestDto;
use App\DTOs\VehicleSearchDto;
use App\Models\VehicleCategory;
use App\Models\Vehicle;
use Carbon\Carbon;
use Tests\TestCase;

class DtoTest extends TestCase
{
    /** @test */
    public function it_creates_booking_create_dto_from_array()
    {
        $dto = BookingCreateDto::fromArray([
            'vehicle_id' => 1,
            'customer_id' => 2,
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-05',
            'notes' => 'Test booking',
            'source' => 'website',
        ]);

        $this->assertEquals(1, $dto->vehicleId);
        $this->assertEquals(2, $dto->customerId);
        $this->assertEquals('2026-07-01', $dto->startDate->format('Y-m-d'));
        $this->assertEquals('2026-07-05', $dto->endDate->format('Y-m-d'));
        $this->assertEquals('Test booking', $dto->notes);
        $this->assertEquals('website', $dto->source?->value);
    }

    /** @test */
    public function it_creates_pricing_request_dto()
    {
        $uniqueName = 'Test ' . uniqid();
        $category = VehicleCategory::create([
            'name' => $uniqueName,
            'slug' => strtolower(str_replace(' ', '-', $uniqueName)),
            'description' => 'Test',
        ]);

        $vehicle = Vehicle::create([
            'name' => 'Test Vehicle ' . uniqid(),
            'category_id' => $category->id,
            'status' => 'active',
            'visibility' => 'public',
            'daily_rate' => 50.00,
            'seats' => 5,
            'type' => 'Sedan',
            'location_id' => null,
        ]);

        $dto = PricingRequestDto::fromArray($vehicle, [
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-03',
        ]);

        $this->assertEquals($vehicle->id, $dto->vehicle->id);
        $this->assertEquals(2, $dto->startDate->diffInDays($dto->endDate));
    }

    /** @test */
    public function it_creates_vehicle_search_dto_from_request()
    {
        $dto = VehicleSearchDto::fromRequest([
            'category_id' => 1,
            'type' => 'SUV',
            'min_price' => 50,
            'max_price' => 150,
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-05',
        ]);

        $this->assertEquals(1, $dto->categoryId);
        $this->assertEquals('SUV', $dto->type);
        $this->assertEquals(50, $dto->minPrice);
        $this->assertEquals(150, $dto->maxPrice);
    }

    /** @test */
    public function it_handles_empty_request()
    {
        $dto = VehicleSearchDto::fromRequest([]);

        $this->assertNull($dto->categoryId);
        $this->assertNull($dto->type);
        $this->assertNull($dto->search);
    }
}
