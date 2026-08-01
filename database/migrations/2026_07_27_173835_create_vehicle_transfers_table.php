<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('vehicle_transfers')) {
            Schema::create('vehicle_transfers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
                $table->foreignId('from_location_id')->nullable()->constrained('locations')->nullOnDelete();
                $table->foreignId('to_location_id')->nullable()->constrained('locations')->nullOnDelete();
                $table->date('transfer_date')->index();
                $table->string('status')->default('scheduled')->index();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_transfers');
    }
};
