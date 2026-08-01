<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('vehicle_extras_booking_services')) {
            Schema::create('vehicle_extras_booking_services', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vehicle_extra_id')->constrained()->cascadeOnDelete();
                $table->foreignId('booking_service_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_extras_booking_services');
    }
};
