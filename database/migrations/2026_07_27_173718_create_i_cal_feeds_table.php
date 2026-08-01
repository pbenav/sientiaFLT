<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('i_cal_feeds')) {
            Schema::create('i_cal_feeds', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('url')->unique();
                $table->string('type')->default('calendar')->comment('calendar, availability');
                $table->boolean('is_active')->default(true);
                $table->integer('sync_interval')->default(3600)->comment('seconds');
                $table->text('last_sync_data')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('i_cal_feeds');
    }
};
