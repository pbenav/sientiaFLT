<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('erp_sync_logs')) {
            Schema::create('erp_sync_logs', function (Blueprint $table) {
                $table->id();
                $table->string('entity_type')->index();
                $table->string('entity_id')->nullable()->index();
                $table->string('action')->index()->comment('create, update, delete, sync');
                $table->text('request_data')->nullable();
                $table->text('response_data')->nullable();
                $table->integer('status_code')->nullable();
                $table->string('status')->default('pending')->index();
                $table->text('error_message')->nullable();
                $table->integer('retry_count')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('erp_sync_logs');
    }
};
