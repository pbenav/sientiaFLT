<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('vehicle_features')) {
            Schema::create('vehicle_features', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('icon')->nullable();
                $table->boolean('is_required')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_features');
    }
};
