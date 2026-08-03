<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('vehicle_price_periods');
        Schema::dropIfExists('vehicle_volume_discounts');

        Schema::table('vehicle_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicle_categories', 'base_price')) {
                $table->decimal('base_price', 10, 2)->default(50.00)->after('description');
            }
        });

        Schema::create('category_price_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_category_id')->constrained('vehicle_categories')->onDelete('cascade');
            $table->foreignId('price_period_id')->constrained('price_periods')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('category_volume_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_category_id')->constrained('vehicle_categories')->onDelete('cascade');
            $table->integer('min_days')->default(1);
            $table->integer('max_days')->nullable();
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_price_periods');
        Schema::dropIfExists('category_volume_discounts');

        Schema::table('vehicle_categories', function (Blueprint $table) {
            $table->dropColumn('base_price');
        });
    }
};
