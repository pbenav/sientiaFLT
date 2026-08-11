<x-filament-panels::page>
    <div class="flex flex-col lg:flex-row gap-6 bg-gray-50/50 p-2 lg:p-4 rounded-xl w-full">
        
        <!-- CATÁLOGO DE VEHÍCULOS (Panel Principal) -->
        <div class="w-full lg:w-2/3 flex flex-col bg-white shadow-xl rounded-2xl border border-gray-100/50 ring-1 ring-gray-900/5">
            <!-- Header & Categories -->
            <div class="p-3 border-b border-gray-100 bg-white/80 backdrop-blur-md flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 z-10 sticky top-0">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-5 h-5 flex-shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Catálogo
                </h2>
                <div class="flex gap-2 overflow-x-auto pb-1 w-full sm:w-auto hide-scrollbar snap-x">
                    <x-filament::button 
                        wire:click="filterByCategory(null)" 
                        color="{{ is_null($activeCategoryId) ? 'primary' : 'gray' }}"
                        size="sm"
                        class="snap-start whitespace-nowrap">
                        Todos
                    </x-filament::button>
                    @foreach($categories as $cat)
                    <x-filament::button 
                        wire:click="filterByCategory({{ $cat->id }})" 
                        color="{{ $activeCategoryId == $cat->id ? 'primary' : 'gray' }}"
                        size="sm"
                        class="snap-start whitespace-nowrap">
                        {{ $cat->name }}
                    </x-filament::button>
                    @endforeach
                </div>
            </div>

            <!-- Vehicles Grid -->
            <div class="flex-1 p-4 bg-gray-50/30">
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                    @forelse($availableVehicles as $vehicle)
                    <div wire:click="addVehicle({{ $vehicle->id }})" 
                         class="group bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-xl hover:border-primary-500 cursor-pointer overflow-hidden transition-all duration-300 flex flex-col h-full transform hover:-translate-y-1 relative">
                        
                        <div class="h-28 bg-gray-50 w-full relative flex items-center justify-center p-3">
                            @if($vehicle->images && $vehicle->images->count() > 0)
                                <img src="{{ asset($vehicle->images->first()->url) }}" class="object-contain h-full w-full mix-blend-multiply transition-transform duration-500 group-hover:scale-105">
                            @else
                                <svg class="w-10 h-10 text-gray-300 transition-transform duration-500 group-hover:scale-105" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 17.25v-10.5M5 17.25v-10.5M3 13.5h18m-14 3h10M4 21h16a1 1 0 001-1V4a1 1 0 00-1-1H4a1 1 0 00-1 1v16a1 1 0 001 1z" />
                                </svg>
                            @endif
                        </div>
                        
                        <div class="p-3 flex-1 flex flex-col justify-between bg-white border-t border-gray-50">
                            <h3 class="font-bold text-gray-800 text-xs leading-tight mb-2 group-hover:text-primary-600 transition-colors">{{ $vehicle->name }}</h3>
                            <div class="text-primary-600 font-bold text-base flex items-center justify-between">
                                <span>{{ number_format($vehicle->category ? $vehicle->category->getCurrentBasePrice() : $vehicle->daily_rate, 2) }}€<span class="text-[10px] font-semibold text-gray-400 ml-0.5">/día</span></span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-16 flex flex-col items-center justify-center text-gray-400">
                        <svg class="w-16 h-16 mb-4 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <p class="text-sm font-medium text-gray-500">No hay vehículos disponibles ahora mismo.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- TICKET Y CARRITO (Panel Lateral) -->
        <div class="w-full lg:w-1/3 flex flex-col bg-white shadow-2xl rounded-2xl border border-gray-100 ring-1 ring-gray-900/5 relative">
            <!-- Header Ticket Activo -->
            <div style="background-color: #111827; color: #ffffff;" class="p-4 flex justify-between items-center shadow-md z-10 border-b border-gray-800">
                <div class="flex flex-col">
                    <span style="color: #9ca3af;" class="text-[9px] font-bold uppercase tracking-widest flex items-center gap-1.5">
                        <span style="background-color: #4ade80;" class="w-1.5 h-1.5 rounded-full animate-pulse"></span>
                        Ticket Activo
                    </span>
                    <span class="font-bold text-lg leading-none mt-1 text-white">
                        {{ $currentSessionId ? substr($currentSessionId, 9) : 'NUEVO TICKET' }}
                    </span>
                </div>
                
                <button x-on:click="$dispatch('open-modal', { id: 'pending-modal' })" 
                        style="background-color: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.1);"
                        class="relative p-2 rounded-lg hover:bg-white/20 transition-all duration-300 flex items-center justify-center group" 
                        title="Tickets Pendientes">
                    <svg style="color: #f3f4f6;" class="w-4 h-4 flex-shrink-0 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    @if(count($pendingSessions) > 0)
                    <span style="background-color: #ef4444; color: #ffffff;" class="absolute -top-1.5 -right-1.5 text-[9px] font-bold px-1.5 py-0.5 rounded-full shadow-sm">{{ count($pendingSessions) }}</span>
                    @endif
                </button>
            </div>

            @if($currentSessionId)
            <div class="flex-1 flex flex-col bg-gray-50/30">
                <!-- Cliente Asociado -->
                <div class="p-4 border-b border-gray-100 bg-white shadow-sm z-0">
                    <label class="flex items-center gap-1.5 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Cliente
                    </label>
                    <select wire:model.live="selectedCustomerId" wire:change="updateCustomer($event.target.value)" class="w-full text-xs font-medium border-gray-200 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 bg-gray-50 py-2">
                        <option value="">Consumidor Final (Anónimo)</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Lista de Vehículos del Carrito -->
                <div class="flex-1 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Vehículos</label>
                        <span class="bg-gray-200 text-gray-700 text-[9px] font-bold px-2 py-0.5 rounded-full">{{ count($cartBookings) }}</span>
                    </div>
                    
                    @if(count($cartBookings) > 0)
                    <div class="space-y-3">
                        @foreach($cartBookings as $bkg)
                        <div class="flex flex-col bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden relative group" x-data="{ start: '{{ \Carbon\Carbon::parse($bkg->start_date)->format('Y-m-d\TH:i') }}', end: '{{ \Carbon\Carbon::parse($bkg->end_date)->format('Y-m-d\TH:i') }}' }">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary-500"></div>
                            
                            <div class="p-3 pl-4">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="mt-0.5">
                                        <div class="font-bold text-gray-800 text-xs">{{ $bkg->vehicle->name ?? 'Desconocido' }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $bkg->booking_number }}</div>
                                    </div>
                                    
                                    <button wire:click="removeBooking({{ $bkg->id }})" 
                                            style="background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca;"
                                            class="flex items-center justify-center p-1.5 rounded text-xs transition-all duration-200 hover:opacity-80"
                                            title="Eliminar">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-2 border border-gray-100 mb-2">
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block text-[9px] text-gray-500 mb-1">Entrega</label>
                                            <input type="datetime-local" x-model="start" x-on:change="$wire.updateDates({{ $bkg->id }}, start, end)" class="w-full text-[11px] text-gray-700 border-gray-200 rounded shadow-sm focus:ring-primary-500 focus:border-primary-500 bg-white py-1 px-2">
                                        </div>
                                        <div>
                                            <label class="block text-[9px] text-gray-500 mb-1">Devolución</label>
                                            <input type="datetime-local" x-model="end" x-on:change="$wire.updateDates({{ $bkg->id }}, start, end)" class="w-full text-[11px] text-gray-700 border-gray-200 rounded shadow-sm focus:ring-primary-500 focus:border-primary-500 bg-white py-1 px-2">
                                        </div>
                                    </div>
                                    <div class="mt-2 flex justify-end">
                                        <span class="text-[9px] bg-primary-50 text-primary-600 px-1.5 py-0.5 rounded font-bold">
                                            {{ $bkg->duration_days }} {{ $bkg->duration_days == 1 ? 'día' : 'días' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between border-t border-gray-100 pt-2">
                                    <span class="text-[11px] text-gray-500">Precio</span>
                                    <div class="text-right">
                                        @if($bkg->discount_amount > 0)
                                        <div class="text-[9px] text-green-600 font-bold leading-none mb-0.5">
                                            -{{ number_format($bkg->discount_amount, 2) }}€
                                        </div>
                                        @endif
                                        <div class="font-bold text-gray-800 text-sm leading-none">{{ number_format($bkg->total_amount, 2) }}€</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="h-32 flex flex-col items-center justify-center text-gray-400 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50/50 mt-2">
                        <svg class="w-8 h-8 mb-1.5 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="text-xs font-medium">Ticket vacío</span>
                    </div>
                    @endif
                </div>

                <!-- Totales y Botones de Acción -->
                <div class="p-4 bg-white border-t border-gray-200 shadow-lg z-10 relative">
                    <div class="flex justify-between items-center mb-1.5 px-1">
                        <span class="text-xs text-gray-500">Subtotal</span>
                        <span class="text-xs font-semibold text-gray-800">{{ number_format($cartSubtotal, 2) }}€</span>
                    </div>
                    <div class="flex justify-between items-center mb-4 px-1">
                        <span class="text-xs text-gray-500">IVA (21%)</span>
                        <span class="text-xs font-semibold text-gray-800">{{ number_format($cartTax, 2) }}€</span>
                    </div>
                    
                    <button type="button" x-on:click="$dispatch('open-modal', { id: 'payment-modal' })" 
                            style="background-color: #10b981; border: 1px solid #059669;"
                            class="w-full text-white shadow-md rounded-xl flex justify-between items-center px-4 py-3 transition-transform duration-200 hover:-translate-y-0.5 hover:shadow-lg">
                        <div class="flex flex-col text-left">
                            <span class="text-white text-[10px] font-bold uppercase tracking-wider mb-0.5 opacity-90">Cobrar Ticket</span>
                            <span class="text-xl font-bold leading-none">{{ number_format($cartTotal, 2) }}€</span>
                        </div>
                        <svg class="w-5 h-5 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                    
                    <div class="mt-3 flex gap-2">
                        <x-filament::button wire:click="clearCart" color="gray" size="sm" icon="heroicon-o-pause" class="flex-1">
                            Pausar
                        </x-filament::button>
                        <x-filament::button wire:click="cancelSession" color="danger" size="sm" icon="heroicon-o-x-mark" class="flex-1">
                            Anular
                        </x-filament::button>
                    </div>
                </div>
            </div>
            @else
            <!-- Estado Inicial: Caja Lista -->
            <div class="flex-1 flex flex-col items-center justify-center p-6 text-center bg-gray-50/50">
                <div class="w-20 h-20 bg-white shadow-sm rounded-full flex items-center justify-center mb-4 text-gray-300 border border-gray-100">
                    <svg class="w-10 h-10 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Caja Lista</h3>
                <p class="text-gray-500 text-xs mb-6 max-w-xs">Selecciona un vehículo del catálogo para comenzar.</p>
                <x-filament::button wire:click="createNewSession" icon="heroicon-o-plus" size="sm">
                    Nuevo Ticket Manual
                </x-filament::button>
            </div>
            @endif
        </div>
    </div>

    <!-- Payment Modal (Filament Native) -->
    <x-filament::modal id="payment-modal" width="md" alignment="center">
        <div class="text-center p-6">
            <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <x-filament::icon icon="heroicon-o-check-circle" class="w-8 h-8 text-green-500" />
            </div>
            <h3 class="text-base text-gray-500 mb-1">Total del Ticket</h3>
            <div class="text-5xl font-bold text-gray-800 mb-2 tracking-tight">{{ number_format($cartTotal, 2) }}€</div>
            <div class="text-sm text-gray-400 mb-8">{{ count($cartBookings) }} {{ count($cartBookings) == 1 ? 'vehículo' : 'vehículos' }} incluidos</div>
            
            <label class="block text-sm font-medium text-gray-700 mb-4 text-left">Selecciona Método de Pago</label>
            <div class="grid grid-cols-3 gap-3 mb-8">
                <button wire:click="$set('paymentMethod', 'efectivo')" class="flex flex-col items-center justify-center p-4 border rounded-xl transition-all duration-200 {{ $paymentMethod === 'efectivo' ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-gray-200 text-gray-500 hover:border-gray-300 hover:bg-gray-50' }}">
                    <x-filament::icon icon="heroicon-o-banknotes" class="w-6 h-6 mb-2" />
                    <span class="text-sm font-medium">Efectivo</span>
                </button>
                <button wire:click="$set('paymentMethod', 'tarjeta')" class="flex flex-col items-center justify-center p-4 border rounded-xl transition-all duration-200 {{ $paymentMethod === 'tarjeta' ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-gray-200 text-gray-500 hover:border-gray-300 hover:bg-gray-50' }}">
                    <x-filament::icon icon="heroicon-o-credit-card" class="w-6 h-6 mb-2" />
                    <span class="text-sm font-medium">Tarjeta</span>
                </button>
                <button wire:click="$set('paymentMethod', 'transferencia')" class="flex flex-col items-center justify-center p-4 border rounded-xl transition-all duration-200 {{ $paymentMethod === 'transferencia' ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-gray-200 text-gray-500 hover:border-gray-300 hover:bg-gray-50' }}">
                    <x-filament::icon icon="heroicon-o-building-library" class="w-6 h-6 mb-2" />
                    <span class="text-sm font-medium">Banco</span>
                </button>
            </div>

            @if($paymentMethod === 'efectivo')
            <div style="padding: 1.5rem;" class="mb-8 bg-gray-50 rounded-xl border border-gray-200 text-left relative overflow-hidden">
                <label class="block text-sm text-gray-600 mb-3 ml-2">Importe entregado por el cliente</label>
                <div class="relative ml-2">
                    <input type="number" step="0.01" wire:model.live.debounce.300ms="amountPaid" 
                           onfocus="this.select()"
                           class="w-full pr-9 pl-4 text-2xl font-bold border border-gray-300 rounded-xl focus:ring-1 focus:ring-primary-500 focus:border-primary-500 py-3 transition-colors text-left" 
                           placeholder="{{ number_format($cartTotal, 2) }}">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 font-medium text-lg">€</span>
                </div>
                
                @if($amountPaid > $cartTotal)
                <div class="mt-4 ml-2 flex justify-between items-center bg-white px-4 py-3 rounded-lg border border-gray-100 shadow-sm">
                    <span class="text-gray-500 text-sm">Cambio a devolver:</span>
                    <span class="text-xl font-bold" style="color: #10b981;">{{ number_format($amountPaid - $cartTotal, 2) }}€</span>
                </div>
                @endif
            </div>
            @endif
            
            <div class="grid grid-cols-3 gap-4">
                <x-filament::button x-on:click="$dispatch('close-modal', { id: 'payment-modal' })" color="gray" class="col-span-1">
                    Cancelar
                </x-filament::button>
                <button wire:click="completePayment" style="background-color: #10b981;" class="col-span-2 py-2 text-white font-semibold rounded-lg shadow-sm hover:opacity-90 transition-all text-sm flex items-center justify-center gap-2">
                    <x-filament::icon icon="heroicon-o-check" class="w-5 h-5 flex-shrink-0" />
                    <span>Confirmar Cobro</span>
                </button>
            </div>
        </div>
    </x-filament::modal>

    <!-- Pending Sessions Modal (Filament Native) -->
    <x-filament::modal id="pending-modal" width="2xl">
        <x-slot name="heading">
            Tickets Aparcados
        </x-slot>
        
        <div>
            @if(count($pendingSessions) === 0)
                <div class="text-center py-12">
                    <x-filament::icon icon="heroicon-o-clipboard-document-check" class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                    <p class="text-gray-500 font-medium text-lg">No hay tickets en espera.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($pendingSessions as $sess)
                    <div wire:click="selectSession('{{ $sess['session_id'] }}'); $dispatch('close-modal', { id: 'pending-modal' })" class="p-5 border border-gray-200 bg-white rounded-xl hover:border-primary-500 hover:shadow-md cursor-pointer flex justify-between items-center group transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-primary-50 group-hover:text-primary-500 transition-colors">
                                <x-filament::icon icon="heroicon-o-shopping-bag" class="w-6 h-6" />
                            </div>
                            <div>
                                <div class="font-bold text-gray-900 text-base group-hover:text-primary-600 transition-colors">Sesión {{ substr($sess['session_id'], 9) }}</div>
                                <div class="text-sm font-medium text-gray-500 mt-1 flex items-center gap-2">
                                    <span>{{ $sess['customer'] ? $sess['customer']['first_name'] . ' ' . $sess['customer']['last_name'] : 'Consumidor Final' }}</span>
                                    <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                    <x-filament::badge size="sm">{{ $sess['items_count'] }} vehículos</x-filament::badge>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-xl text-gray-900">{{ number_format($sess['total_amount'], 2) }}€</div>
                            <div class="text-xs text-gray-400 mt-1">
                                {{ \Carbon\Carbon::parse($sess['created_at'])->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </x-filament::modal>
</x-filament-panels::page>