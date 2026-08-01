<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
                $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
                $table->string('invoice_number')->unique();
                $table->string('erp_document_id')->nullable()->index();
                $table->string('type')->default('invoice')->comment('invoice, credit_note, quote');
                $table->date('issue_date');
                $table->date('due_date')->nullable();
                $table->decimal('subtotal', 12, 2)->default(0);
                $table->decimal('tax_amount', 12, 2)->default(0);
                $table->decimal('total_amount', 12, 2)->default(0);
                $table->string('currency_code')->default('EUR');
                $table->string('status')->default('draft')->index();
                $table->text('notes')->nullable();
                $table->string('pdf_path')->nullable();
                $table->string('erp_sync_status')->default('pending');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
