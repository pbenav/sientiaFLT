<?php

namespace App\Jobs;

use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Booking;
use App\Services\ErpSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncWithErpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    public function __construct(
        public readonly string $entityType,
        public readonly int $entityId
    ) {}

    public function handle(ErpSyncService $erpSyncService): void
    {
        $entity = match ($this->entityType) {
            'vehicle' => Vehicle::find($this->entityId),
            'customer' => Customer::find($this->entityId),
            'booking' => Booking::find($this->entityId),
            default => null,
        };

        if (!$entity) {
            $this->fail(new \Exception("Entity {$this->entityType} with ID {$this->entityId} not found"));
            return;
        }

        match ($this->entityType) {
            'vehicle' => $erpSyncService->syncVehicle($entity),
            'customer' => $erpSyncService->syncCustomer($entity),
            'booking' => $erpSyncService->syncBooking($entity),
            default => throw new \Exception("Unknown entity type: {$this->entityType}"),
        };
    }
}
