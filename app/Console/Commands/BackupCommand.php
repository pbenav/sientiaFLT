<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BackupCommand extends Command
{
    protected $signature = 'backup:run {--days=30 : Number of days to keep backups}';

    protected $description = 'Run database and file backups';

    public function handle(): int
    {
        $this->info('Starting backup...');

        $this->info('Backing up database...');
        $dbResult = Artisan::call('db:backup', [
            '--destination' => 'backups',
            '--filename' => 'database_' . date('Y-m-d_His') . '.zip',
        ]);

        if ($dbResult === 0) {
            $this->info('Database backup completed.');
        } else {
            $this->error('Database backup failed.');
            return Command::FAILURE;
        }

        $this->info('Cleaning old backups...');
        $this->cleanOldBackups((int) $this->option('days'));

        $this->info('Backup completed successfully!');
        return Command::SUCCESS;
    }

    protected function cleanOldBackups(int $days): void
    {
        $before = now()->subDays($days);
        $deleted = Storage::disk('local')->files('backups');

        $count = 0;
        foreach ($deleted as $file) {
            if (Storage::disk('local')->lastModified($file) < $before->timestamp) {
                Storage::disk('local')->delete($file);
                $count++;
            }
        }

        $this->info("Deleted {$count} old backup(s).");
    }
}
