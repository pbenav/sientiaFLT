<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('booking_services')) {
            Schema::create('booking_services', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
                $table->string('service_type')->index();
                $table->string('name');
                $table->decimal('unit_price', 12, 2)->default(0);
                $table->decimal('total_price', 12, 2)->default(0);
                $table->integer('quantity')->default(1);
                $table->string('calculation_type')->default('fixed')->comment('fixed, daily, per_day, per_km');
                $table->decimal('daily_rate', 12, 2)->nullable();
                $table->decimal('per_km_rate', 12, 2)->nullable();
                $table->integer('included_km')->default(0);
                $table->decimal('extra_km_rate', 12, 2)->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_services');
    }
};
