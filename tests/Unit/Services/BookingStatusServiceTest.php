<?php

namespace Tests\Unit\Services;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Services\AvailabilityService;
use App\Services\BookingStatusService;
use Carbon\Carbon;
use Tests\TestCase;

class BookingStatusServiceTest extends TestCase
{
    protected BookingStatusService $service;

    protected static int $bookingCounter = 0;

    protected function setUp(): void
    {
        parent::setUp();
        \App\Models\Booking::query()->delete();
        $this->service = new BookingStatusService();
    }

    protected function tearDown(): void
    {
        \App\Models\Booking::query()->delete();
        \App\Models\Invoice::query()->delete();
        \App\Models\Customer::query()->where('email', 'like', 'status-test%')->delete();
        parent::tearDown();
    }

    protected function createBooking(array $overrides = []): Booking
    {
        self::$bookingCounter++;
        return Booking::create(array_merge([
            'customer_id' => null,
            'vehicle_id' => null,
            'booking_number' => 'STATUS-TEST-' . self::$bookingCounter . '-' . uniqid(),
            'start_date' => Carbon::parse('2026-06-01'),
            'end_date' => Carbon::parse('2026-06-03'),
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ], $overrides));
    }

    /** @test */
    public function it_can_transition_from_pending_to_confirmed()
    {
        $booking = $this->createBooking(['status' => 'pending']);

        $result = $this->service->confirm($booking);

        $this->assertEquals('confirmed', $result->status);
        $this->assertStringContainsString('pending → confirmed', $result->notes);
    }

    /** @test */
    public function it_can_transition_from_confirmed_to_active()
    {
        $booking = $this->createBooking(['status' => 'confirmed']);

        $result = $this->service->activate($booking);

        $this->assertEquals('active', $result->status);
    }

    /** @test */
    public function it_can_transition_from_active_to_completed()
    {
        $booking = $this->createBooking(['status' => 'active']);

        $result = $this->service->complete($booking);

        $this->assertEquals('completed', $result->status);
    }

    /** @test */
    public function it_can_cancel_from_pending()
    {
        $booking = $this->createBooking(['status' => 'pending']);

        $result = $this->service->cancel($booking, 'Customer cancelled');

        $this->assertEquals('cancelled', $result->status);
        $this->assertStringContainsString('Customer cancelled', $result->notes);
    }

    /** @test */
    public function it_cannot_transition_from_pending_to_active()
    {
        $booking = $this->createBooking(['status' => 'pending']);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid transition from \'pending\' to \'active\'');

        $this->service->activate($booking);
    }

    /** @test */
    public function it_cannot_transition_from_completed_to_anything()
    {
        $booking = $this->createBooking(['status' => 'completed', 'payment_status' => 'paid']);

        $this->expectException(\InvalidArgumentException::class);
        $this->service->cancel($booking);
    }

    /** @test */
    public function it_returns_valid_transitions()
    {
        $booking = $this->createBooking(['status' => 'pending']);

        $transitions = $this->service->getValidTransitions($booking);

        $this->assertEquals(['confirmed', 'cancelled'], $transitions);
    }

    /** @test */
    public function it_returns_no_valid_transitions_for_completed()
    {
        $booking = $this->createBooking(['status' => 'completed', 'payment_status' => 'paid']);

        $transitions = $this->service->getValidTransitions($booking);

        $this->assertEquals([], $transitions);
    }

    /** @test */
    public function it_tracks_transition_history_in_notes()
    {
        $booking = $this->createBooking(['status' => 'pending', 'notes' => 'Original note']);

        $this->service->confirm($booking, 'Payment received');
        $this->service->activate($booking);

        $booking->refresh();

        $this->assertStringContainsString('Original note', $booking->notes);
        $this->assertStringContainsString('pending → confirmed', $booking->notes);
        $this->assertStringContainsString('Payment received', $booking->notes);
        $this->assertStringContainsString('confirmed → active', $booking->notes);
    }
}
