<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('vehicle_category')) {
            Schema::create('vehicle_category', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
                $table->foreignId('vehicle_category_id')->constrained()->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['vehicle_id', 'vehicle_category_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_category');
    }
};
