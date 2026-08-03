<section class="ex-section" style="background: #F4F5F6; min-height: calc(100vh - 200px);">
    <div class="container-ex">
        
        <div class="text-center mb-10">
            <h1 class="ex-section-title">{{ __('Gestión de Reservas') }}</h1>
            <p class="ex-section-subtitle">{{ __('Introduce tu localizador y correo electrónico para ver o modificar tu reserva.') }}</p>
            <hr class="ex-accent-line">
        </div>

        <div style="max-width: 800px; margin: 0 auto; position: relative; z-index: 10;">
            
            @if (session('error'))
                <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 16px; border-radius: 8px; margin-bottom: 24px; font-weight: 500;">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; padding: 16px; border-radius: 8px; margin-bottom: 24px; font-weight: 500;">
                    {{ session('success') }}
                </div>
            @endif

            @if(!$booking)
                <div style="background: #ffffff; border-radius: 16px; padding: 40px; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">
                    <form wire:submit.prevent="search" style="display: grid; gap: 24px;">
                        <div>
                            <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">{{ __('Localizador de Reserva') }} <span style="color: #EA001E;">*</span></label>
                            <input type="text" wire:model.defer="locator" required placeholder="Ej. BK-20260802-ABCD" style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
                        </div>
                        <div>
                            <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">{{ __('Correo Electrónico') }} <span style="color: #EA001E;">*</span></label>
                            <input type="email" wire:model.defer="email" required placeholder="El email que usaste para reservar" style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
                        </div>
                        
                        <div style="text-align: center; margin-top: 10px;">
                            <button type="submit" class="ex-btn ex-btn-primary" style="width: 100%; padding: 16px 48px; border-radius: 10px; font-weight: 700; font-size: 17px; cursor: pointer; border: none;">
                                <span wire:loading.remove wire:target="search">{{ __('Buscar Reserva') }}</span>
                                <span wire:loading wire:target="search">{{ __('Buscando...') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <!-- Booking Details -->
                <div style="background: #ffffff; border-radius: 16px; padding: 40px; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 20px;">
                        <div>
                            <h2 style="font-size: 24px; font-weight: 800; color: #161829; font-family: 'Space Grotesk', sans-serif;">Reserva {{ $booking->booking_number }}</h2>
                            <p style="color: #64748b; font-size: 14px; margin-top: 4px;">{{ $booking->customer->first_name }} {{ $booking->customer->last_name }}</p>
                        </div>
                        <div>
                            @if($booking->status == 'pending')
                                <span style="background: #fef3c7; color: #d97706; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 700; text-transform: uppercase;">Pendiente</span>
                            @elseif($booking->status == 'confirmed')
                                <span style="background: #dbeafe; color: #2563eb; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 700; text-transform: uppercase;">Confirmada</span>
                            @else
                                <span style="background: #f1f5f9; color: #475569; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 700; text-transform: uppercase;">{{ ucfirst($booking->status) }}</span>
                            @endif
                        </div>
                    </div>

                    <div style="display: flex; gap: 20px; margin-bottom: 30px; align-items: center;">
                        @if($booking->vehicle->primaryImage)
                            <img src="{{ $booking->vehicle->primaryImage->url }}" alt="{{ $booking->vehicle->name }}" style="width: 120px; height: 90px; object-fit: cover; border-radius: 12px;">
                        @endif
                        <div>
                            <h3 style="font-weight: 700; color: #161829; font-size: 20px; margin: 0;">{{ $booking->vehicle->name }}</h3>
                            <p style="color: #64748b; font-size: 14px; margin: 0;">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</p>
                        </div>
                    </div>

                    @if(!$editMode)
                        <!-- View Mode -->
                        <div style="background: #f8fafc; border-radius: 12px; padding: 20px; margin-bottom: 30px; border: 1px solid #e2e8f0; display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div>
                                <span style="display: block; font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">Recogida</span>
                                <span style="font-size: 16px; font-weight: 600; color: #161829;">{{ $booking->start_date->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <span style="display: block; font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">Devolución</span>
                                <span style="font-size: 16px; font-weight: 600; color: #161829;">{{ $booking->end_date->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 10px; border-top: 1px solid #e2e8f0;">
                            <span style="font-size: 16px; font-weight: 700; color: #161829;">Total de la Reserva</span>
                            <span style="font-size: 24px; font-weight: 800; color: #EA001E; font-family: 'Space Grotesk', sans-serif;">€{{ number_format($booking->total_amount, 2) }}</span>
                        </div>

                        <div style="margin-top: 30px; display: flex; gap: 15px;">
                            <button wire:click="enableEdit" class="ex-btn ex-btn-primary" style="flex: 1; padding: 14px; border-radius: 10px; font-weight: 700; font-size: 16px; border: none; cursor: pointer;">
                                Modificar Fechas
                            </button>
                            <button wire:click="$set('booking', null)" class="ex-btn" style="flex: 1; padding: 14px; border-radius: 10px; font-weight: 700; font-size: 16px; border: 1px solid #d1d5db; background: #ffffff; color: #374151; cursor: pointer;">
                                Volver
                            </button>
                        </div>
                    @else
                        <!-- Edit Mode -->
                        <div style="background: #f8fafc; border-radius: 12px; padding: 20px; margin-bottom: 30px; border: 1px solid #e2e8f0;">
                            <h4 style="font-weight: 700; color: #161829; font-size: 16px; margin-bottom: 15px;">Modificar Fechas</h4>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                <div>
                                    <label style="display: block; color: #374151; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 5px;">{{ __('Recogida') }}</label>
                                    <input type="date" wire:model.live="new_start_date" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; background: #ffffff;" min="{{ date('Y-m-d') }}">
                                </div>
                                <div>
                                    <label style="display: block; color: #374151; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 5px;">{{ __('Devolución') }}</label>
                                    <input type="date" wire:model.live="new_end_date" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; background: #ffffff;" min="{{ $new_start_date ?? date('Y-m-d') }}">
                                </div>
                            </div>
                            
                            <div style="background: #ffffff; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 20px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; color: #475569;">
                                    <span>{{ __('Tarifa Base') }} ({{ $new_duration_days }} {{ $new_duration_days == 1 ? __('día') : __('días') }})</span>
                                    <span>€{{ number_format($new_base_price, 2) }}</span>
                                </div>
                                @if($new_discount > 0)
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; color: #10b981; font-weight: 600;">
                                    <span>{{ __('Descuentos') }}</span>
                                    <span>-€{{ number_format($new_discount, 2) }}</span>
                                </div>
                                @endif
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; color: #475569; border-top: 1px dashed #e2e8f0; padding-top: 8px;">
                                    <span>{{ __('Subtotal') }}</span>
                                    <span>€{{ number_format($new_subtotal, 2) }}</span>
                                </div>
                                @if($new_tax_amount > 0)
                                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: #475569;">
                                    <span>{{ $new_tax_name }}</span>
                                    <span>€{{ number_format($new_tax_amount, 2) }}</span>
                                </div>
                                @endif
                                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px solid #e2e8f0;">
                                    <span style="font-size: 14px; font-weight: 700; color: #161829;">Nuevo Total Estimado</span>
                                    <span style="font-size: 20px; font-weight: 800; color: #EA001E; font-family: 'Space Grotesk', sans-serif;">€{{ number_format($new_total_price, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; gap: 15px;">
                            <button wire:click="saveModification" class="ex-btn ex-btn-primary" style="flex: 1; padding: 14px; border-radius: 10px; font-weight: 700; font-size: 16px; border: none; cursor: pointer;">
                                Guardar Cambios
                            </button>
                            <button wire:click="cancelEdit" class="ex-btn" style="flex: 1; padding: 14px; border-radius: 10px; font-weight: 700; font-size: 16px; border: 1px solid #d1d5db; background: #ffffff; color: #374151; cursor: pointer;">
                                Cancelar
                            </button>
                        </div>
                    @endif

                </div>
            @endif
        </div>
    </div>
</section>
