<div>
    @if(session('message'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('message') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="/" class="text-blue-600 hover:text-blue-800 text-sm font-medium mb-4 inline-block flex items-center">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Home
            </a>
            <h2 class="text-2xl font-bold text-gray-900">Book a Vehicle</h2>
            @if($vehicle)
                <p class="text-gray-500 mt-1">{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})</p>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <form wire:submit.prevent="submit" class="space-y-6">
                        <!-- Vehicle Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Vehicle</label>
                            <select wire:model="vehicle_id" class="select-field" wire:change="calculatePrice">
                                <option value="">Select a vehicle</option>
                                @php
                                    $vehicles = \App\Models\Vehicle::active()->available()->get();
                                @endphp
                                @foreach($vehicles as $v)
                                    <option value="{{ $v->id }}">{{ $v->name }} - €{{ number_format($v->daily_rate, 2) }}/day</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pick-up Date</label>
                                <input type="date" wire:model="start_date" min="{{ date('Y-m-d') }}" class="input-field" wire:change="calculatePrice">
                                @error('start_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Return Date</label>
                                <input type="date" wire:model="end_date" min="{{ $start_date ?? date('Y-m-d') }}" class="input-field" wire:change="calculatePrice">
                                @error('end_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Customer Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Customer</label>
                            <select wire:model="customer_id" class="select-field">
                                <option value="">Select customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->full_name }} @if($customer->company_name)({{ $customer->company_name }})@endif</option>
                                @endforeach
                            </select>
                            @error('customer_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Extras -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-3">Add-ons & Extras</h3>
                            <div class="space-y-3">
                                @foreach($extras as $extra)
                                <label class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <div class="flex items-center">
                                        <input type="checkbox" value="{{ $extra->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-4 w-4" wire:model="extras">
                                        <span class="ml-3 text-sm text-gray-700">{{ $extra->name }}</span>
                                        @if($extra->description)
                                            <span class="ml-2 text-xs text-gray-400">- {{ $extra->description }}</span>
                                        @endif
                                    </div>
                                    <span class="text-sm font-semibold text-blue-600">+€{{ number_format($extra->price, 2) }}{{ $extra->calculation_type === 'per_day' ? '/day' : '' }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Special Requests -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Special Requests</label>
                            <textarea class="input-field" rows="3" placeholder="Any special requirements..."></textarea>
                        </div>

                        <!-- Submit -->
                        <div class="flex justify-end pt-4">
                            <button type="submit" class="btn-secondary text-white px-8 py-3 rounded-lg font-semibold flex items-center">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Confirm Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Price Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-20">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Price Summary</h3>

                    @if($duration_days > 0)
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Duration</span>
                                <span class="font-medium">{{ $duration_days }} day{{ $duration_days > 1 ? 's' : '' }}</span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Base price</span>
                                <span class="font-medium">€{{ number_format($subtotal, 2) }}</span>
                            </div>

                            @if($tax_amount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Tax</span>
                                <span class="font-medium">€{{ number_format($tax_amount, 2) }}</span>
                            </div>
                            @endif

                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between">
                                    <span class="text-lg font-bold text-gray-900">Total</span>
                                    <span class="text-2xl font-bold text-blue-600">€{{ number_format($total_price, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                            <p class="text-xs text-blue-700">
                                <svg class="inline h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Free cancellation up to 48 hours before pick-up
                            </p>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400">
                            <svg class="h-12 w-12 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm">Select dates to see pricing</p>
                        </div>
                    @endif

                    <!-- Trust badges -->
                    <div class="mt-6 pt-4 border-t border-gray-100 space-y-3">
                        <div class="flex items-center text-xs text-gray-500">
                            <svg class="h-4 w-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            Secure payment
                        </div>
                        <div class="flex items-center text-xs text-gray-500">
                            <svg class="h-4 w-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            No hidden fees
                        </div>
                        <div class="flex items-center text-xs text-gray-500">
                            <svg class="h-4 w-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Full insurance included
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
