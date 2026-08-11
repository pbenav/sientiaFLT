<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('license_plate')->unique()->nullable()->comment('Matrícula');
            $table->string('vin')->unique()->nullable()->comment('Número de bastidor');
            $table->string('color')->nullable();
            $table->json('extras')->nullable()->comment('Baúl, cascos, etc.');
            $table->string('status')->default('active')->comment('active, maintenance, retired, in_use');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_units');
    }
};
