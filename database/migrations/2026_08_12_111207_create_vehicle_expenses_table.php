<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_unit_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // repair, maintenance, insurance, tax, other
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('invoice_number')->nullable();
            $table->timestamps();
        });

        // Drop the old maintenance_cost column from vehicle_units
        Schema::table('vehicle_units', function (Blueprint $table) {
            $table->dropColumn('maintenance_cost');
        });
    }

    public function down(): void
    {
        Schema::table('vehicle_units', function (Blueprint $table) {
            $table->decimal('maintenance_cost', 10, 2)->default(0);
        });
        
        Schema::dropIfExists('vehicle_expenses');
    }
};
