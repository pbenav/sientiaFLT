<?php

namespace Tests\Unit\Services;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Tests\TestCase;

class AvailabilityServiceTest extends TestCase
{
    protected AvailabilityService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AvailabilityService();

        // Create a customer for bookings
        $this->customer = Customer::create([
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'email' => 'test-' . uniqid() . '@example.com',
            'phone' => '123456789',
        ]);
    }

    protected function createVehicle(array $overrides = []): Vehicle
    {
        $category = VehicleCategory::firstOrCreate(
            ['slug' => 'test-category-' . uniqid()],
            [
                'name' => 'Test Category ' . uniqid(),
                'description' => 'Test',
            ]
        );

        return Vehicle::create(array_merge([
            'name' => 'Test Vehicle ' . uniqid(),
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

    protected function createBooking(array $data): Booking
    {
        return Booking::create(array_merge([
            'customer_id' => $this->customer->id,
            'vehicle_id' => null,
            'status' => 'confirmed',
            'payment_status' => 'paid',
        ], $data));
    }

    /** @test */
    public function it_returns_only_available_vehicles()
    {
        $vehicle = $this->createVehicle();

        $this->createBooking([
            'vehicle_id' => $vehicle->id,
            'booking_number' => 'BK-' . uniqid(),
            'start_date' => Carbon::parse('2026-06-01'),
            'end_date' => Carbon::parse('2026-06-05'),
            'status' => 'confirmed',
        ]);

        $available = $this->service->getAvailableVehicles(
            startDate: Carbon::parse('2026-06-02'),
            endDate: Carbon::parse('2026-06-04'),
        );

        $this->assertNotContains($vehicle, $available);
    }

    /** @test */
    public function it_allows_booking_after_conflict()
    {
        $vehicle = $this->createVehicle();

        $this->createBooking([
            'vehicle_id' => $vehicle->id,
            'booking_number' => 'BK-' . uniqid(),
            'start_date' => Carbon::parse('2026-06-01'),
            'end_date' => Carbon::parse('2026-06-05'),
            'status' => 'confirmed',
        ]);

        $available = $this->service->getAvailableVehicles(
            startDate: Carbon::parse('2026-06-06'),
            endDate: Carbon::parse('2026-06-08'),
        );

        $this->assertTrue($available->contains($vehicle));
    }

    /** @test */
    public function it_allows_booking_before_conflict()
    {
        $vehicle = $this->createVehicle();

        $this->createBooking([
            'vehicle_id' => $vehicle->id,
            'booking_number' => 'BK-' . uniqid(),
            'start_date' => Carbon::parse('2026-06-01'),
            'end_date' => Carbon::parse('2026-06-05'),
            'status' => 'confirmed',
        ]);

        $available = $this->service->getAvailableVehicles(
            startDate: Carbon::parse('2026-05-25'),
            endDate: Carbon::parse('2026-05-30'),
        );

        $this->assertTrue($available->contains($vehicle));
    }

    /** @test */
    public function it_filters_by_category()
    {
        $category = VehicleCategory::firstOrCreate(
            ['slug' => 'suv-category-' . uniqid()],
            ['name' => 'SUV Category ' . uniqid(), 'description' => 'SUV']
        );

        $vehicle = $this->createVehicle([
            'category_id' => $category->id,
            'type' => 'SUV',
            'seats' => 7,
        ]);

        $available = $this->service->getAvailableVehicles(
            categoryId: $category->id,
            startDate: Carbon::parse('2026-06-10'),
            endDate: Carbon::parse('2026-06-12'),
        );

        $this->assertTrue($available->contains($vehicle));
    }

    /** @test */
    public function it_filters_by_type()
    {
        $vehicle = $this->createVehicle([
            'type' => 'SUV',
            'seats' => 7,
        ]);

        $available = $this->service->getAvailableVehicles(
            type: 'SUV',
            startDate: Carbon::parse('2026-06-10'),
            endDate: Carbon::parse('2026-06-12'),
        );

        $this->assertTrue($available->contains($vehicle));
    }

    /** @test */
    public function it_filters_by_price_range()
    {
        $available = $this->service->getAvailableVehicles(
            minPrice: 0,
            maxPrice: 1000,
            startDate: Carbon::parse('2026-06-10'),
            endDate: Carbon::parse('2026-06-12'),
        );

        foreach ($available as $vehicle) {
            $this->assertLessThanOrEqual(1000, $vehicle->daily_rate);
        }
    }

    /** @test */
    public function it_returns_conflicting_bookings()
    {
        $vehicle = $this->createVehicle();

        $booking = $this->createBooking([
            'vehicle_id' => $vehicle->id,
            'booking_number' => 'BK-' . uniqid(),
            'start_date' => Carbon::parse('2026-06-01'),
            'end_date' => Carbon::parse('2026-06-05'),
            'status' => 'confirmed',
        ]);

        $conflicts = $this->service->getConflictingBookings(
            $vehicle,
            Carbon::parse('2026-06-02'),
            Carbon::parse('2026-06-04'),
        );

        $this->assertTrue($conflicts->contains($booking));
    }

    /** @test */
    public function it_excludes_booking_from_conflicts()
    {
        $vehicle = $this->createVehicle();

        $this->createBooking([
            'vehicle_id' => $vehicle->id,
            'booking_number' => 'BK-' . uniqid(),
            'start_date' => Carbon::parse('2026-06-01'),
            'end_date' => Carbon::parse('2026-06-05'),
            'status' => 'confirmed',
        ]);

        $conflicts = $this->service->getConflictingBookings(
            $vehicle,
            Carbon::parse('2026-06-02'),
            Carbon::parse('2026-06-04'),
        );

        $this->assertCount(1, $conflicts);
    }

    /** @test */
    public function it_checks_is_vehicle_available()
    {
        $vehicle = $this->createVehicle();

        $this->createBooking([
            'vehicle_id' => $vehicle->id,
            'booking_number' => 'BK-' . uniqid(),
            'start_date' => Carbon::parse('2026-06-01'),
            'end_date' => Carbon::parse('2026-06-05'),
            'status' => 'confirmed',
        ]);

        $notAvailable = $this->service->isVehicleAvailable(
            $vehicle,
            Carbon::parse('2026-06-02'),
            Carbon::parse('2026-06-04'),
        );

        $this->assertFalse($notAvailable);

        $available = $this->service->isVehicleAvailable(
            $vehicle,
            Carbon::parse('2026-06-10'),
            Carbon::parse('2026-06-12'),
        );

        $this->assertTrue($available);
    }

    /** @test */
    public function it_excludes_cancelled_bookings_from_conflicts()
    {
        $vehicle = $this->createVehicle();

        $this->createBooking([
            'vehicle_id' => $vehicle->id,
            'booking_number' => 'BK-' . uniqid(),
            'start_date' => Carbon::parse('2026-06-01'),
            'end_date' => Carbon::parse('2026-06-05'),
            'status' => 'cancelled',
            'payment_status' => 'refunded',
        ]);

        $conflicts = $this->service->getConflictingBookings(
            $vehicle,
            Carbon::parse('2026-06-02'),
            Carbon::parse('2026-06-04'),
        );

        $this->assertFalse($conflicts->contains(fn($b) => $b->status === 'cancelled'));
    }

    /** @test */
    public function it_excludes_completed_bookings_from_conflicts()
    {
        $vehicle = $this->createVehicle();

        $this->createBooking([
            'vehicle_id' => $vehicle->id,
            'booking_number' => 'BK-' . uniqid(),
            'start_date' => Carbon::parse('2026-05-01'),
            'end_date' => Carbon::parse('2026-05-05'),
            'status' => 'completed',
            'payment_status' => 'paid',
        ]);

        $conflicts = $this->service->getConflictingBookings(
            $vehicle,
            Carbon::parse('2026-06-02'),
            Carbon::parse('2026-06-04'),
        );

        $this->assertFalse($conflicts->contains(fn($b) => $b->status === 'completed'));
    }
}
