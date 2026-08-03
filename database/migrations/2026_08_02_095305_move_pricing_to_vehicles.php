<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('category_price_periods');
        Schema::dropIfExists('category_volume_discounts');

        Schema::create('vehicle_price_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('price_period_id')->constrained('price_periods')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('vehicle_volume_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->integer('min_days')->default(1);
            $table->integer('max_days')->nullable();
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_price_periods');
        Schema::dropIfExists('vehicle_volume_discounts');
    }
};
