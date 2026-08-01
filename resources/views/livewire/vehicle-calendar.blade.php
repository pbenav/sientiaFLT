<div>
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    @if($vehicle)
                        {{ $vehicle->name }} - Booking Calendar
                    @else
                        Booking Calendar
                    @endif
                </h2>
                <p class="text-gray-500 text-sm mt-1">View availability for selected vehicle</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="/book/{{ $vehicle_id ?? 1 }}/{{ auth()->id() ?? 1 }}" class="btn-secondary text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Book Now
                </a>
            </div>
        </div>

        <!-- Month Navigation -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <button wire:click="previousMonth" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <h3 class="text-xl font-bold text-gray-900">
                    {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}
                </h3>
                <button wire:click="nextMonth" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- Legend -->
            <div class="flex flex-wrap gap-4 mb-6 text-xs">
                <div class="flex items-center">
                    <span class="w-4 h-4 rounded bg-blue-500 inline-block mr-2"></span>
                    <span class="text-gray-600">Today</span>
                </div>
                <div class="flex items-center">
                    <span class="w-4 h-4 rounded bg-green-500 inline-block mr-2"></span>
                    <span class="text-gray-600">Active Booking</span>
                </div>
                <div class="flex items-center">
                    <span class="w-4 h-4 rounded bg-yellow-500 inline-block mr-2"></span>
                    <span class="text-gray-600">Pending</span>
                </div>
                <div class="flex items-center">
                    <span class="w-4 h-4 rounded bg-red-500 inline-block mr-2"></span>
                    <span class="text-gray-600">Confirmed</span>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 gap-1 mb-2">
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="text-center text-sm font-semibold text-gray-500 py-2">{{ $day }}</div>
                @endforeach
            </div>

            <div class="grid grid-cols-7 gap-1">
                @php
                    $firstDay = \Carbon\Carbon::create($year, $month, 1)->dayOfWeek;
                    $daysInMonth = \Carbon\Carbon::create($year, $month, 1)->daysInMonth;
                    $today = \Carbon\Carbon::now();
                    $bookingDates = [];
                    foreach($bookings as $booking) {
                        $start = \Carbon\Carbon::parse($booking->start_date);
                        $end = \Carbon\Carbon::parse($booking->end_date);
                        for($d = $start->copy(); $d->lte($end); $d->addDay()) {
                            if ($d->month == $month && $d->year == $year) {
                                $bookingDates[$d->day] = $booking->status;
                            }
                        }
                    }
                @endphp

                @for($i = 0; $i < $firstDay; $i++)
                <div class="calendar-day bg-gray-50 text-gray-300"></div>
                @endfor

                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $isToday = $today->day == $day && $today->month == $month && $today->year == $year;
                        $hasBooking = isset($bookingDates[$day]);
                        $bookingStatus = $bookingDates[$day] ?? null;
                    @endphp
                    <div class="calendar-day {{ $isToday ? 'is-today' : '' }} {{ $hasBooking ? 'has-booking' : '' }} cursor-pointer hover:bg-gray-50 border border-gray-100"
                         @if($hasBooking)
                            title="{{ ucfirst($bookingStatus) }} booking on {{ $month }}/{{ $day }}/{{ $year }}"
                         @endif>
                        <span class="text-sm">{{ $day }}</span>
                        @if($hasBooking)
                            <span class="text-xs opacity-75">{{ ucfirst(substr($bookingStatus, 0, 3)) }}</span>
                        @endif
                    </div>
                @endfor

                @php $totalCells = $firstDay + $daysInMonth; @endphp
                @for($i = $totalCells; $i < (($totalCells - 1) / 7 + 1) * 7; $i++)
                <div class="calendar-day bg-gray-50 text-gray-300"></div>
                @endfor
            </div>
        </div>

        <!-- Bookings List -->
        @if(count($bookings) > 0)
        <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Current Bookings</h3>
            <div class="space-y-3">
                @foreach($bookings as $booking)
                <div class="flex items-center justify-between p-3 border border-gray-100 rounded-lg">
                    <div class="flex items-center">
                        <span class="w-2 h-2 rounded-full mr-3 {{ $booking->status === 'confirmed' ? 'bg-red-500' : ($booking->status === 'active' ? 'bg-green-500' : 'bg-yellow-500') }}"></span>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $booking->booking_number }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->customer ? $booking->customer->full_name : 'Unknown' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">{{ $booking->start_date->format('M d') }} - {{ $booking->end_date->format('M d') }}</p>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $booking->status === 'confirmed' ? 'bg-red-100 text-red-700' : ($booking->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
