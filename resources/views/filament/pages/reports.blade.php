<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-6">
                <dt class="text-sm font-medium text-gray-500">Total Bookings</dt>
                <dd class="mt-1 text-3xl font-bold text-gray-900">{{ \App\Models\Booking::count() }}</dd>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <dt class="text-sm font-medium text-gray-500">Total Revenue</dt>
                <dd class="mt-1 text-3xl font-bold text-green-600">€{{ number_format(\App\Models\Payment::where('status', 'completed')->sum('amount'), 2) }}</dd>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <dt class="text-sm font-medium text-gray-500">Active Customers</dt>
                <dd class="mt-1 text-3xl font-bold text-blue-600">{{ \App\Models\Customer::where('is_active', true)->count() }}</dd>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <dt class="text-sm font-medium text-gray-500">Occupancy Rate</dt>
                <dd class="mt-1 text-3xl font-bold text-purple-600">{{ $this->getOccupancyRate() }}%</dd>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Top Vehicles by Bookings</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Vehicle</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bookings</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Daily Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($this->getTopVehicles() as $vehicle)
                        <tr>
                            <td class="px-4 py-2">{{ $vehicle['brand'] }} {{ $vehicle['model'] }}</td>
                            <td class="px-4 py-2">{{ $vehicle['bookings_count'] ?? 0 }}</td>
                            <td class="px-4 py-2">€{{ number_format($vehicle['daily_rate'] ?? 0, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Top Customers</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bookings</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($this->getTopCustomers() as $customer)
                        <tr>
                            <td class="px-4 py-2">{{ $customer['first_name'] }} {{ $customer['last_name'] }}</td>
                            <td class="px-4 py-2">{{ $customer['bookings_count'] ?? 0 }}</td>
                            <td class="px-4 py-2">{{ $customer['email'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Booking Status Distribution</h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @foreach(['pending' => 'bg-yellow-100 text-yellow-800', 'confirmed' => 'bg-blue-100 text-blue-800', 'active' => 'bg-green-100 text-green-800', 'completed' => 'bg-gray-100 text-gray-800', 'cancelled' => 'bg-red-100 text-red-800'] as $status => $colors)
                <div class="rounded-lg p-4 {{ $colors }}">
                    <div class="text-center">
                        <div class="text-2xl font-bold">{{ $this->getBookingsByStatus()[$status] ?? 0 }}</div>
                        <div class="text-sm capitalize">{{ $status }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-panels::page>
