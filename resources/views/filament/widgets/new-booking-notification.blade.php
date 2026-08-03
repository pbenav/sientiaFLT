<div x-data="{
    show: {{ $count > 0 ? 'true' : 'false' }},
    autoRefresh() {
        setInterval(() => {
            this.$wire.call('refreshBookings');
            if (this.$wire.count > 0 && !this.show) {
                this.show = true;
                this.notify();
            }
        }, 60000);
    },
    notify() {
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification('sientiaFLT - Nueva Reserva', {
                body: '{{ $count }} nueva(s) reserva(s) pendiente(s)',
                icon: '/favicon.ico',
            });
        }
    },
    dismiss() {
        this.$wire.call('dismissAll');
        this.show = false;
    }
}" x-init="autoRefresh(); if ('Notification' in window && Notification.permission === 'default') { Notification.requestPermission(); }"
class="bg-warning/10 dark:bg-warning/5 border border-warning/20 dark:border-warning/20 rounded-lg p-4">

    @if ($count > 0)
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-warning" viewBox="0 0 20 20" fill="currentColor">
                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.95 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H16a1 1 0 001-1V5a1 1 0 00-1-1h-2z" />
            </svg>
            <h3 class="text-sm font-semibold text-warning">
                {{ $count }} nueva{{ $count > 1 ? 's' : '' }} reserva{{ $count > 1 ? 's' : '' }} (24h)
            </h3>
        </div>
        <button wire:click='dismissAll' @click.prevent="dismiss()"
            class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
            Descartar
        </button>
    </div>

    <ul class="space-y-2">
        @foreach ($newBookings as $booking)
        <li class="bg-white dark:bg-gray-800 rounded p-3 border border-gray-100 dark:border-gray-700">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ $booking['booking_number'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        {{ $booking['customer']['first_name'] ?? 'Cliente' }} {{ $booking['customer']['last_name'] ?? '' }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                        {{ $booking['vehicle']['name'] ?? 'Vehículo' }} ·
                        {{ \Carbon\Carbon::parse($booking['created_at'])->diffForHumans() }}
                    </p>
                </div>
                <a href="{{ route('filament.admin.resources.bookings.edit', $booking['id']) }}"
                    class="text-xs bg-warning text-white px-2 py-1 rounded hover:bg-warning/80 transition-colors">
                    Ver
                </a>
            </div>
        </li>
        @endforeach
    </ul>
    @else
    <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
        </svg>
        <span class="text-sm">Sin nuevas reservas en las últimas 24h</span>
    </div>
    @endif
</div>
