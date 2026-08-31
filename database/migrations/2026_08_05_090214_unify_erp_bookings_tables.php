<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // 1. Remove foreign keys that block dropping tables
        if (Schema::hasColumn('invoices', 'tpv_ticket_id')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropForeign(['tpv_ticket_id']);
                $table->dropColumn('tpv_ticket_id');
            });
        }

        if (Schema::hasColumn('invoices', 'alquiler_id')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropForeign(['alquiler_id']);
                $table->dropColumn('alquiler_id');
            });
        }

        if (Schema::hasColumn('bookings', 'tpv_ticket_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropForeign(['tpv_ticket_id']);
                $table->dropColumn('tpv_ticket_id');
            });
        }

        // 2. Drop the redundant tables
        Schema::dropIfExists('alquileres');
        Schema::dropIfExists('ticket_tpv_lineas');
        Schema::dropIfExists('ticket_tpv');

        // 3. Add ERP fields to bookings
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'fecha_entrega')) {
                $table->dateTime('fecha_entrega')->nullable()->after('end_date');
            }
            if (!Schema::hasColumn('bookings', 'fecha_devolucion')) {
                $table->dateTime('fecha_devolucion')->nullable()->after('fecha_entrega');
            }
            if (!Schema::hasColumn('bookings', 'payment_method_id')) {
                $table->foreignId('payment_method_id')->nullable()->constrained('forma_pagos')->nullOnDelete()->after('payment_status');
            }
            if (!Schema::hasColumn('bookings', 'discount_amount')) {
                $table->decimal('discount_amount', 12, 2)->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('bookings', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->after('customer_id');
            }
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // No down needed as this drops old tables permanently. 
        // We will just remove the columns from bookings.
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['payment_method_id']);
            $table->dropColumn(['fecha_entrega', 'fecha_devolucion', 'payment_method_id', 'discount_amount', 'user_id']);
        });
    }
};
