<?php

namespace App\Jobs;

use App\Models\ICalFeed;
use App\Services\ICalSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncICalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function __construct(
        public readonly ?int $feedId = null
    ) {}

    public function handle(ICalSyncService $iCalService): void
    {
        $feed = $this->feedId
            ? ICalFeed::find($this->feedId)
            : ICalFeed::where('is_active', true)->get();

        if ($this->feedId && $feed) {
            $iCalService->syncFeed($feed);
        } elseif (!$this->feedId && is_iterable($feed)) {
            foreach ($feed as $f) {
                $iCalService->syncFeed($f);
            }
        }
    }
}
