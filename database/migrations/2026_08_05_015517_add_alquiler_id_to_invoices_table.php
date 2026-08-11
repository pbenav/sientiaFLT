<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'alquiler_id')) {
                $table->foreignId('alquiler_id')->nullable()->after('booking_id')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('invoices', 'tpv_ticket_id')) {
                $table->foreignId('tpv_ticket_id')->nullable()->after('alquiler_id')->constrained('ticket_tpv')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['tpv_ticket_id']);
            $table->dropColumn('tpv_ticket_id');
            $table->dropForeign(['alquiler_id']);
            $table->dropColumn('alquiler_id');
        });
    }
};
