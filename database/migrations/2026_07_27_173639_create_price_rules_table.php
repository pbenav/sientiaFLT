<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('price_rules')) {
            Schema::create('price_rules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();
                $table->string('rule_type')->index()->comment('daily, weekly, monthly, seasonal, holiday, special, km');
                $table->string('name')->nullable();
                $table->date('start_date')->nullable()->index();
                $table->date('end_date')->nullable()->index();
                $table->integer('min_days')->default(0);
                $table->integer('max_days')->nullable();
                $table->integer('min_km')->default(0);
                $table->integer('max_km')->nullable();
                $table->decimal('base_price', 12, 2)->default(0);
                $table->decimal('discount_percentage', 5, 2)->default(0);
                $table->decimal('discount_amount', 12, 2)->default(0);
                $table->decimal('price_per_km', 12, 2)->nullable();
                $table->decimal('extra_km_price', 12, 2)->nullable();
                $table->integer('priority')->default(0);
                $table->boolean('is_active')->default(true);
                $table->text('conditions')->nullable()->comment('JSON conditions');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('price_rules');
    }
};
