<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('vehicle_feature')) {
            Schema::create('vehicle_feature', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
                $table->foreignId('vehicle_feature_id')->constrained()->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['vehicle_id', 'vehicle_feature_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_feature');
    }
};
