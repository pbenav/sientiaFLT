<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Models\ICalFeed;
use Sabre\VObject\Node\Event as IcalEvent;
use Sabre\VObject\Reader;

class ICalSyncService
{
    public function fetchEvents(string $url): array
    {
        $content = file_get_contents($url);
        if ($content === false) {
            return [];
        }

        $vcal = Reader::read($content);
        $events = [];

        foreach ($vcal->children() as $component) {
            if ($component->name === 'VEVENT') {
                $events[] = [
                    'summary' => (string) $component->SUMMARY,
                    'description' => (string) ($component->DESCRIPTION ?? ''),
                    'location' => (string) ($component->LOCATION ?? ''),
                    'start' => $component->DTSTART->getDateTime(),
                    'end' => $component->DTEND->getDateTime(),
                    'uid' => (string) $component->UID,
                    'status' => (string) ($component->STATUS ?? 'CONFIRMED'),
                ];
            }
        }

        return $events;
    }

    public function syncFeed(ICalFeed $feed): void
    {
        $events = $this->fetchEvents($feed->url);

        $feed->update([
            'last_sync_data' => $events,
            'synced_at' => now(),
        ]);
    }

    public function checkAvailability(Vehicle $vehicle, $startDate, $endDate): bool
    {
        $conflicts = 0;

        foreach (ICalFeed::where('is_active', true)->get() as $feed) {
            $events = $this->fetchEvents($feed->url);

            foreach ($events as $event) {
                $eventStart = $event['start'];
                $eventEnd = $event['end'];

                if ($startDate < $eventEnd && $endDate > $eventStart) {
                    $conflicts++;
                }
            }
        }

        return $conflicts === 0;
    }
}
