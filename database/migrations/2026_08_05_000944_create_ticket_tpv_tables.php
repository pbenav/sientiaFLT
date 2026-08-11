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
        // --- forma_pagos (métodos de pago) ---
        if (!Schema::hasTable('forma_pagos')) {
            Schema::create('forma_pagos', function (Blueprint $table) {
                $table->id();
                $table->string('codigo')->unique();
                $table->string('nombre');
                $table->string('tipo')->nullable(); // contado, transferencia, tarjeta, pagare, recibo_bancario, efectivo
                $table->boolean('activo')->default(true);
                $table->text('descripcion')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // --- ticket_tpv (cabecera del ticket de caja) ---
        if (!Schema::hasTable('ticket_tpv')) {
            Schema::create('ticket_tpv', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->unique()->nullable();
            $table->string('numero')->nullable()->unique();
            $table->string('status')->default('open'); // open, completed, cancelled
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('iva_total', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('descuento_porcentaje', 5, 2)->default(0);
            $table->decimal('descuento_importe', 10, 2)->default(0);
            $table->string('payment_method')->nullable(); // cash, card, mixed
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->decimal('change_given', 10, 2)->nullable();
            $table->decimal('amount_due', 10, 2)->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            });
        }

        // --- ticket_tpv_linea (productos/vehículos del ticket) ---
        if (!Schema::hasTable('ticket_tpv_lineas')) {
            Schema::create('ticket_tpv_lineas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_tpv_id')->constrained('ticket_tpv')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();
            $table->string('vehicle_name')->nullable();
            $table->string('category_name')->nullable();
            $table->string('description')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(21);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
            });
        }

        // --- alquileres (albarán de entrega del vehículo) ---
        if (!Schema::hasTable('alquileres')) {
            Schema::create('alquileres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('ticket_tpv_id')->nullable()->constrained('ticket_tpv')->nullOnDelete();
            $table->string('alquiler_number')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('vehicle_id')->constrained();
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('status')->default('borrador'); // borrador, confirmado, activo, completado, anulado
            $table->string('payment_status')->default('pendiente'); // pendiente, parcial, pagado, reembolsado
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('base_imponible', 10, 2)->default(0);
            $table->decimal('iva', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->decimal('amount_due', 10, 2)->default(0);
            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->foreignId('payment_method_id')->nullable()->constrained('forma_pagos')->nullOnDelete();
            $table->text('observaciones')->nullable();
            $table->text('observaciones_internas')->nullable();
            $table->date('fecha_entrega')->nullable();
            $table->date('fecha_devolucion')->nullable();
            $table->string('start_location')->nullable();
            $table->string('end_location')->nullable();
            $table->string('return_location')->nullable();
            $table->string('currency_code')->default('EUR');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            });
        }

        // --- actualizar bookings con tpv_ticket_id ---
        if (!Schema::hasColumn('bookings', 'tpv_ticket_id')) {
                Schema::table('bookings', function (Blueprint $table) {
                $table->foreignId('tpv_ticket_id')->nullable()->after('id')->constrained('ticket_tpv')->nullOnDelete();
            });
        }

        // --- actualizar invoices con alquiler_id ---
        if (!Schema::hasColumn('invoices', 'alquiler_id')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->foreignId('alquiler_id')->nullable()->after('booking_id')->constrained('alquileres')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['alquiler_id']);
            $table->dropColumn('alquiler_id');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['tpv_ticket_id']);
            $table->dropColumn('tpv_ticket_id');
        });

        Schema::dropIfExists('ticket_tpv_lineas');
        Schema::dropIfExists('ticket_tpv');
        Schema::dropIfExists('alquileres');
        Schema::dropIfExists('forma_pagos');
    }
};
