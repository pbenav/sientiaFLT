<?php

namespace Tests\Unit\Services;

use App\Models\Booking;
use App\Services\BookingNumberGenerator;
use Tests\TestCase;

class BookingNumberGeneratorTest extends TestCase
{
    protected BookingNumberGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generator = new BookingNumberGenerator();
    }

    protected function tearDown(): void
    {
        \App\Models\Booking::query()->delete();
        \App\Models\Invoice::query()->delete();
        \App\Models\Customer::query()->where('email', 'like', 'test%')->delete();
        parent::tearDown();
    }

    /** @test */
    public function it_generates_booking_numbers()
    {
        $number1 = $this->generator->generate();
        $number2 = $this->generator->generate();

        $this->assertMatchesRegularExpression('/^BK-\d{8}-\d{4}$/', $number1);
        $this->assertMatchesRegularExpression('/^BK-\d{8}-\d{4}$/', $number2);
    }

    /** @test */
    public function it_generates_invoice_numbers()
    {
        $number1 = $this->generator->generateInvoice();
        $number2 = $this->generator->generateInvoice();

        $this->assertMatchesRegularExpression('/^INV-\d{4}-\d{5}$/', $number1);
        $this->assertMatchesRegularExpression('/^INV-\d{4}-\d{5}$/', $number2);
    }

    /** @test */
    public function it_generates_locator_codes()
    {
        $locator1 = $this->generator->generateLocator();
        $locator2 = $this->generator->generateLocator();

        $this->assertMatchesRegularExpression('/^BK-\d{8}-[A-Z0-9]{4}$/', $locator1);
        $this->assertMatchesRegularExpression('/^BK-\d{8}-[A-Z0-9]{4}$/', $locator2);
        $this->assertNotEquals($locator1, $locator2);
    }

    /** @test */
    public function it_generates_unique_booking_numbers()
    {
        Booking::query()->delete();

        $numbers = [];
        for ($i = 0; $i < 10; $i++) {
            $number = $this->generator->generate();
            $numbers[] = $number;
            Booking::create([
                'customer_id' => null,
                'vehicle_id' => null,
                'booking_number' => 'GEN-' . $number . '-' . $i,
                'start_date' => \Carbon\Carbon::parse('2026-06-01'),
                'end_date' => \Carbon\Carbon::parse('2026-06-03'),
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);
        }

        $this->assertCount(10, array_unique($numbers));
    }

    /** @test */
    public function it_generates_unique_invoice_numbers()
    {
        $numbers = [];
        $customer = \App\Models\Customer::create([
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'email' => 'test' . uniqid() . '@example.com',
            'phone' => '555-0000',
        ]);
        $booking = Booking::create([
            'customer_id' => $customer->id,
            'vehicle_id' => null,
            'booking_number' => 'INV-TEST-BOOKING-' . uniqid(),
            'start_date' => \Carbon\Carbon::parse('2026-06-01'),
            'end_date' => \Carbon\Carbon::parse('2026-06-03'),
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);
        for ($i = 0; $i < 10; $i++) {
            $number = $this->generator->generateInvoice();
            $numbers[] = $number;
            \App\Models\Invoice::create([
                'booking_id' => $booking->id,
                'customer_id' => $customer->id,
                'invoice_number' => $number,
                'issue_date' => \Carbon\Carbon::now(),
                'status' => 'draft',
            ]);
        }

        $this->assertCount(10, array_unique($numbers));
    }

    /** @test */
    public function it_generates_unique_locators()
    {
        $locators = [];
        for ($i = 0; $i < 100; $i++) {
            $locators[] = $this->generator->generateLocator();
        }

        $this->assertCount(100, array_unique($locators));
    }

    /** @test */
    public function it_increments_sequentially()
    {
        Booking::query()->delete();

        $number1 = $this->generator->generate();
        Booking::create([
            'customer_id' => null,
            'vehicle_id' => null,
            'booking_number' => 'SEQ-TEST-' . uniqid(),
            'start_date' => \Carbon\Carbon::parse('2026-06-01'),
            'end_date' => \Carbon\Carbon::parse('2026-06-03'),
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);
        $number2 = $this->generator->generate();

        $seq1 = (int) substr($number1, -4);
        $seq2 = (int) substr($number2, -4);

        $this->assertEquals($seq2, $seq1 + 1);
    }

    /** @test */
    public function it_uses_current_year()
    {
        $number = $this->generator->generate();
        $year = (string) date('Y');

        $this->assertStringContainsString($year, $number);
    }
}
