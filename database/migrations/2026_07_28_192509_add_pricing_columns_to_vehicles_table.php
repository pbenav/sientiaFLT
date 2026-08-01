<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('vehicles')) {
            return;
        }

        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'daily_rate')) {
                $table->decimal('daily_rate', 10, 2)->default(50.00)->after('erp_sync_data')->comment('Base daily rate');
            }
            if (!Schema::hasColumn('vehicles', 'weekly_rate')) {
                $table->decimal('weekly_rate', 10, 2)->nullable()->after('daily_rate');
            }
            if (!Schema::hasColumn('vehicles', 'monthly_rate')) {
                $table->decimal('monthly_rate', 10, 2)->nullable()->after('weekly_rate');
            }
            if (!Schema::hasColumn('vehicles', 'security_deposit')) {
                $table->decimal('security_deposit', 10, 2)->default(500.00)->after('monthly_rate');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['daily_rate', 'weekly_rate', 'monthly_rate', 'security_deposit']);
        });
    }
};
