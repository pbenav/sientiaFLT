<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('bookings')) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
                $table->foreignId('erp_documento_id')->nullable()->unique()->index();
                $table->string('booking_number')->unique();
                $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
                $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
                $table->dateTime('start_date');
                $table->dateTime('end_date');
                $table->string('start_location')->nullable();
                $table->string('end_location')->nullable();
                $table->string('return_location')->nullable();
                $table->boolean('is_round_trip')->default(false);
                $table->integer('driver_age')->nullable();
                $table->boolean('has_additional_driver')->default(false);
                $table->string('status')->default('pending')->index();
                $table->string('payment_status')->default('unpaid')->index();
                $table->decimal('subtotal', 12, 2)->default(0);
                $table->decimal('tax_amount', 12, 2)->default(0);
                $table->decimal('total_amount', 12, 2)->default(0);
                $table->decimal('amount_paid', 12, 2)->default(0);
                $table->decimal('amount_due', 12, 2)->default(0);
                $table->string('currency_code')->default('EUR');
                $table->decimal('deposit_amount', 12, 2)->default(0);
                $table->text('special_requests')->nullable();
                $table->text('customer_notes')->nullable();
                $table->text('internal_notes')->nullable();
                $table->string('booking_source')->default('website');
                $table->string('utm_source')->nullable();
                $table->string('utm_medium')->nullable();
                $table->string('utm_campaign')->nullable();
                $table->string('referral_code')->nullable();
                $table->boolean('is_confirmed')->default(false);
                $table->boolean('is_paid')->default(false);
                $table->boolean('is_active')->default(true);
                $table->string('erp_sync_status')->default('pending')->index();
                $table->json('erp_sync_data')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['start_date', 'end_date']);
                $table->index(['status', 'is_active']);
                $table->index(['payment_status', 'is_active']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
