<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('erp_tercio_id')->nullable()->unique()->index();
                $table->string('email')->unique()->index();
                $table->string('phone')->nullable();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('company_name')->nullable();
                $table->string('nif_cif')->nullable();
                $table->string('address')->nullable();
                $table->string('city')->nullable();
                $table->string('province')->nullable();
                $table->string('postal_code')->nullable();
                $table->string('country')->default('ES');
                $table->text('notes')->nullable();
                $table->boolean('is_active')->default(true);
                $table->boolean('is_company')->default(false);
                $table->string('locale')->default('es');
                $table->string('currency_code')->default('EUR');
                $table->string('erp_sync_status')->default('pending')->index();
                $table->json('erp_sync_data')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
