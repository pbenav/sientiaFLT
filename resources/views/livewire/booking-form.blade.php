<div>
    @if(session('message'))
        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; padding: 16px; border-radius: 8px; margin-bottom: 24px; font-weight: 500;">
            {{ session('message') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 16px; border-radius: 8px; margin-bottom: 24px; font-weight: 500;">
            {{ session('error') }}
        </div>
    @endif

    <div style="background: #ffffff; border-radius: 16px; padding: 40px; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">
        
        <!-- Detailed Summary -->
        <div style="background: #f8fafc; border-radius: 12px; padding: 20px; margin-bottom: 30px; border: 1px solid #e2e8f0;">
            @if($vehicle)
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 15px;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    @if($vehicle->primaryImage && $vehicle->primaryImage->url)
                        <img src="{{ $vehicle->primaryImage->url }}" alt="{{ $vehicle->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                    @endif
                    <div>
                        <h3 style="font-weight: 700; color: #161829; font-size: 18px; margin: 0;">{{ $vehicle->name }}</h3>
                        <p style="color: #64748b; font-size: 13px; margin: 0;">{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Fechas -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; color: #374151; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 5px;">{{ __('Recogida') }}</label>
                    <input type="date" wire:model.live="start_date" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; background: #ffffff;" min="{{ date('Y-m-d') }}">
                    @error('start_date') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label style="display: block; color: #374151; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 5px;">{{ __('Devolución') }}</label>
                    <input type="date" wire:model.live="end_date" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; background: #ffffff;" min="{{ $start_date ?? date('Y-m-d') }}">
                    @error('end_date') <span style="color: #ef4444; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Precios Desglosados -->
            @if($duration_days > 0)
            <div style="background: #ffffff; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; color: #475569;">
                    <span>{{ __('Tarifa Base') }} ({{ $duration_days }} {{ $duration_days == 1 ? __('día') : __('días') }})</span>
                    <span>€{{ number_format($base_price, 2) }}</span>
                </div>
                @if($discount > 0)
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; color: #10b981; font-weight: 600;">
                    <span>{{ __('Descuentos') }}</span>
                    <span>-€{{ number_format($discount, 2) }}</span>
                </div>
                @endif
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; color: #475569; border-top: 1px dashed #e2e8f0; padding-top: 8px;">
                    <span>{{ __('Subtotal') }}</span>
                    <span>€{{ number_format($subtotal, 2) }}</span>
                </div>
                @if($tax_amount > 0)
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: #475569;">
                    <span>{{ $tax_name }}</span>
                    <span>€{{ number_format($tax_amount, 2) }}</span>
                </div>
                @endif
                <div style="display: flex; justify-content: space-between; border-top: 1px solid #e2e8f0; padding-top: 12px;">
                    <span style="font-size: 16px; font-weight: 700; color: #161829;">{{ __('Total a Pagar') }}</span>
                    <span style="font-size: 24px; font-weight: 800; color: #EA001E; font-family: 'Space Grotesk', sans-serif;">€{{ number_format($total_price, 2) }}</span>
                </div>
            </div>
            @endif
        </div>

        <h2 style="font-size: 20px; font-weight: 700; color: #161829; margin-bottom: 24px;">{{ __('Tus Datos Personales') }}</h2>

        <form wire:submit.prevent="submit" style="display: grid; gap: 24px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div>
                    <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">{{ __('Nombre') }} <span style="color: #EA001E;">*</span></label>
                    <input type="text" wire:model.defer="first_name" required style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
                </div>
                <div>
                    <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">{{ __('Apellidos') }} <span style="color: #EA001E;">*</span></label>
                    <input type="text" wire:model.defer="last_name" required style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div>
                    <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">{{ __('Email') }} <span style="color: #EA001E;">*</span></label>
                    <input type="email" wire:model.defer="email" required style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
                </div>
                <div>
                    <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">{{ __('Teléfono') }} <span style="color: #EA001E;">*</span></label>
                    <input type="text" wire:model.defer="phone" required style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
                </div>
            </div>

            <div>
                <label style="display: block; color: #374151; font-size: 14px; font-weight: 600; margin-bottom: 8px;">{{ __('DNI / NIE / Pasaporte') }} <span style="color: #EA001E;">*</span></label>
                <input type="text" wire:model.defer="nif" required style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 15px; color: #374151; background: #f9fafb;">
            </div>

            <div style="text-align: center; margin-top: 10px;">
                <button type="submit" class="ex-btn ex-btn-primary" style="width: 100%; padding: 16px 48px; border-radius: 10px; font-weight: 700; font-size: 17px; cursor: pointer; border: none;">
                    <span wire:loading.remove wire:target="submit">{{ __('Confirmar y Reservar') }}</span>
                    <span wire:loading wire:target="submit">{{ __('Procesando...') }}</span>
                </button>
                <p style="font-size: 12px; color: #94a3b8; margin-top: 12px;">
                    {{ __('Al hacer clic en "Confirmar", aceptas nuestros') }} <a href="#" style="color: #EA001E; text-decoration: underline;">{{ __('términos y condiciones') }}</a>.
                    @if($vehicle && $vehicle->security_deposit > 0)
                        <br>{{ __('Se requerirá una fianza de') }} <strong>€{{ number_format($vehicle->security_deposit, 2) }}</strong> {{ __('al recoger el vehículo.') }}
                    @endif
                </p>
            </div>
        </form>
    </div>
    
    <div style="margin-top: 30px; text-align: center;">
        <p style="color: #64748b; font-size: 14px;">
            <svg style="width: 16px; height: 16px; display: inline; margin-bottom: 2px; color: #10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            {{ __('Pago 100% seguro. Sin costes ocultos.') }}
        </p>
    </div>
</div>
