<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ErpSyncService;
use App\Models\ErpSyncLog;
use Illuminate\Support\Facades\Log;

class SyncErpCommand extends Command
{
    protected $signature = 'erp:sync {--entity= : Specific entity to sync (vehicle, customer, booking, all)}';

    protected $description = 'Sync local data with SientiaERP';

    protected ErpSyncService $erpSyncService;

    public function __construct(ErpSyncService $erpSyncService)
    {
        parent::__construct();
        $this->erpSyncService = $erpSyncService;
    }

    public function handle(): int
    {
        $entity = $this->option('entity');

        $this->info('Starting ERP sync...');

        $synced = 0;
        $failed = 0;

        match ($entity) {
            'vehicle', 'all' => $this->syncEntity('Vehicles', fn() => $this->erpSyncService->syncAllPendingVehicles(), compact(&'synced', &'failed)),
            'customer', 'all' => $this->syncEntity('Customers', fn() => $this->erpSyncService->syncAllPendingCustomers(), compact(&'synced', &'failed)),
            'booking', 'all' => $this->syncEntity('Bookings', fn() => $this->erpSyncService->syncAllPendingBookings(), compact(&'synced', &'failed)),
            default => $this->error('Invalid entity. Use: vehicle, customer, booking, or all'),
                return Command::FAILURE,
        };

        $this->info('ERP sync completed!');
        $this->info("Synced: {$synced} | Failed: {$failed}");

        return Command::SUCCESS;
    }

    protected function syncEntity(string $name, callable $sync, array &$vars): void
    {
        $this->info("Syncing {$name}...");

        try {
            $sync();
            $this->info("{$name} synced successfully.");
            $vars['synced']++;
        } catch (\Exception $e) {
            Log::error("ERP sync failed for {$name}", ['error' => $e->getMessage()]);
            $this->error("{$name} sync failed: " . $e->getMessage());
            $vars['failed']++;
        }
    }
}
