<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ErpSyncLog;

class ClearExpiredSyncLogsCommand extends Command
{
    protected $signature = 'erp:clear-logs {--days=30 : Number of days to keep logs}';

    protected $description = 'Clear expired ERP sync logs';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $before = now()->subDays($days);

        $deleted = ErpSyncLog::where('created_at', '<', $before)->delete();

        $this->info("Deleted {$deleted} sync log(s) older than {$days} days.");

        return Command::SUCCESS;
    }
}
