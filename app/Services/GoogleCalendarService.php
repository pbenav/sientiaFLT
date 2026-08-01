<?php

namespace App\Services;

use App\Models\Booking;
use App\Services\SientiaErpClient;
use Google\Calendar\CalendarListEntry;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Illuminate\Support\Facades\Log;

class GoogleCalendarService
{
    protected $calendarService;
    protected string $calendarId;

    public function __construct()
    {
        $this->calendarService = $this->getCalendarService();
        $this->calendarId = config('services.google_calendar.primary_calendar_id', 'primary');
    }

    public function addBookingToCalendar(Booking $booking): ?Event
    {
        try {
            $event = new Event([
                'summary' => 'Reserva #' . $booking->booking_number . ' - ' . $booking->vehicle->name,
                'description' => $booking->customer->first_name . ' ' . $booking->customer->last_name .
                    PHP_EOL . 'Email: ' . $booking->customer->email .
                    PHP_EOL . 'Teléfono: ' . ($booking->customer->phone ?? 'N/A') .
                    PHP_EOL . 'Total: €' . $booking->total_amount,
                'start' => new EventDateTime([
                    'dateTime' => $booking->start_date->toIso8601String(),
                    'timeZone' => $booking->location->timezone ?? 'Europe/Madrid',
                ]),
                'end' => new EventDateTime([
                    'dateTime' => $booking->end_date->toIso8601String(),
                    'timeZone' => $booking->location->timezone ?? 'Europe/Madrid',
                ]),
                'status' => $booking->status === 'confirmed' ? 'confirmed' : 'tentative',
                'colorId' => $this->getBookingColor($booking->status),
            ]);

            $createdEvent = $this->calendarService->events->insert($this->calendarId, $event);

            return $createdEvent;
        } catch (\Exception $e) {
            Log::error('Google Calendar error', ['error' => $e->getMessage(), 'booking_id' => $booking->id]);
            return null;
        }
    }

    public function removeBookingFromCalendar(string $eventId): bool
    {
        try {
            $this->calendarService->events->delete($this->calendarId, $eventId);
            return true;
        } catch (\Exception $e) {
            Log::error('Google Calendar delete error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getCalendarEvents(string $vehicleId, ?string $startDate = null, ?string $endDate = null): array
    {
        $params = [
            'timeMin' => ($startDate ?? now()->toIso8601String()),
            'timeMax' => ($endDate ?? now()->addYear()->toIso8601String()),
            'singleEvents' => true,
            'orderBy' => 'startTime',
        ];

        $events = $this->calendarService->events->listEvents($this->calendarId, $params);
        return $events->getItems() ?? [];
    }

    protected function getCalendarService()
    {
        $client = new \Google\Client();
        $client->setClientId(config('services.google_calendar.client_id'));
        $client->setClientSecret(config('services.google_calendar.client_secret'));
        $client->setRedirectUri(config('services.google_calendar.redirect_uri'));
        $client->addScope('https://www.googleapis.com/auth/calendar');

        $tokenPath = storage_path('google-calendar-token.json');
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        return new \Google\Service\Calendar($client);
    }

    protected function getBookingColor(string $status): string
    {
        return match ($status) {
            'pending' => '1',
            'confirmed' => '2',
            'active' => '9',
            'completed' => '10',
            'cancelled' => '3',
            default => '1',
        };
    }
}
