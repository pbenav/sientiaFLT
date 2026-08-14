<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicle_units', function (Blueprint $table) {
            $table->date('purchase_date')->nullable()->after('color');
            $table->decimal('purchase_price', 10, 2)->nullable()->after('purchase_date');
            $table->integer('current_km')->default(0)->after('purchase_price');
            $table->decimal('maintenance_cost', 10, 2)->default(0)->after('current_km');
        });
    }

    public function down(): void
    {
        Schema::table('vehicle_units', function (Blueprint $table) {
            $table->dropColumn(['purchase_date', 'purchase_price', 'current_km', 'maintenance_cost']);
        });
    }
};
