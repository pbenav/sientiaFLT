<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('google_calendar_tokens')) {
            Schema::create('google_calendar_tokens', function (Blueprint $table) {
                $table->id();
                $table->string('account_name')->unique();
                $table->text('access_token')->nullable();
                $table->text('refresh_token')->nullable();
                $table->string('expires_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('google_calendar_tokens');
    }
};
