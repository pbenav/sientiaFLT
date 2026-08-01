<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('vehicles')) {
            Schema::create('vehicles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('erp_product_id')->nullable()->unique()->index();
                $table->string('sku')->unique()->nullable()->index();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('brand')->nullable();
                $table->string('model')->nullable();
                $table->string('year')->nullable();
                $table->string('license_plate')->nullable()->index();
                $table->string('category')->nullable();
                $table->string('type')->nullable();
                $table->string('body_type')->nullable();
                $table->string('fuel_type')->nullable();
                $table->string('transmission')->nullable();
                $table->string('engine')->nullable();
                $table->string('power_hp')->nullable();
                $table->integer('seats')->default(5);
                $table->integer('doors')->default(5);
                $table->integer('luggage_large')->default(1);
                $table->integer('luggage_small')->default(2);
                $table->integer('automatic_gears')->nullable();
                $table->string('color')->nullable();
                $table->string('km')->nullable();
                $table->string('emission_code')->nullable();
                $table->string('energy_type')->nullable();
                $table->string('transmission_type')->nullable();
                $table->string('drive_type')->nullable();
                $table->string('gearbox')->nullable();
                $table->string('image_url')->nullable();
                $table->text('description')->nullable();
                $table->text('features')->nullable()->comment('JSON features array');
                $table->boolean('is_active')->default(true);
                $table->boolean('is_available')->default(true);
                $table->boolean('show_on_homepage')->default(false);
                $table->boolean('show_on_fleet')->default(true);
                $table->boolean('show_on_category')->default(true);
                $table->boolean('is_new')->default(false);
                $table->boolean('is_recommended')->default(false);
                $table->boolean('is_featured')->default(false);
                $table->boolean('is_eco')->default(false);
                $table->boolean('is_electric')->default(false);
                $table->boolean('is_hybrid')->default(false);
                $table->boolean('is_erhverv')->default(false);
                $table->boolean('is_company')->default(false);
                $table->boolean('is_show_on_homepage')->default(true);
                $table->boolean('is_show_on_fleet')->default(true);
                $table->boolean('is_show_on_category')->default(true);
                $table->boolean('is_new_listing')->default(false);
                $table->boolean('is_recommended_listing')->default(false);
                $table->boolean('is_featured_listing')->default(false);
                $table->boolean('is_eco_listing')->default(false);
                $table->boolean('is_electric_listing')->default(false);
                $table->boolean('is_hybrid_listing')->default(false);
                $table->boolean('is_erhverv_listing')->default(false);
                $table->boolean('is_company_listing')->default(false);
                $table->decimal('daily_rate', 10, 2)->default(50.00)->comment('Base daily rate');
                $table->decimal('weekly_rate', 10, 2)->nullable();
                $table->decimal('monthly_rate', 10, 2)->nullable();
                $table->decimal('security_deposit', 10, 2)->default(500.00);
                $table->string('erp_sync_status')->default('pending')->index();
                $table->json('erp_sync_data')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['is_active', 'is_available']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
