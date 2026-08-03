<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers\PaymentsRelationManager;
use App\Filament\Resources\BookingResource\RelationManagers\InvoiceRelationManager;
use App\Filament\Resources\BookingResource\RelationManagers\ReviewsRelationManager;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Actions\UpdateBookingTotals;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Operations';

    public static function form(Form $form): Form
    {
        $updateTotals = UpdateBookingTotals::make();

        return $form
            ->schema([
                Forms\Components\Section::make(__('Información de la Reserva'))
                    ->schema([
                        Forms\Components\TextInput::make('booking_number')
                            ->label(__('Localizador'))
                            ->required()
                            ->maxLength(50)
                            ->default(\App\Services\BookingNumberGenerator::generate())
                            ->disabled(),
                        Forms\Components\Select::make('customer_id')
                            ->label(__('Cliente'))
                            ->options(Customer::orderBy('first_name')->get()->pluck('full_name', 'id'))
                            ->searchable()
                            ->required()
                            ->reactive(),
                        Forms\Components\Placeholder::make('customer_details')
                            ->label(__('Detalles del Cliente'))
                            ->content(function (Forms\Get $get) {
                                if ($customerId = $get('customer_id')) {
                                    $customer = Customer::find($customerId);
                                    if ($customer) {
                                        return new \Illuminate\Support\HtmlString(
                                            "<strong>Email:</strong> {$customer->email}<br>" .
                                            "<strong>Teléfono:</strong> {$customer->phone}<br>" .
                                            "<strong>DNI/NIF:</strong> {$customer->nif_cif}"
                                        );
                                    }
                                }
                                return '-';
                            }),
                        Forms\Components\Select::make('vehicle_id')
                            ->label(__('Vehículo'))
                            ->options(Vehicle::where('is_active', true)->orderBy('name')->get()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated($updateTotals),
                        Forms\Components\Select::make('location_id')
                            ->label(__('Lugar de Recogida/Devolución'))
                            ->options(Location::orderBy('name')->get()->pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),
                    ])->columns(2),

                Forms\Components\Section::make(__('Fechas'))
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->label(__('Fecha de Recogida'))
                            ->required()
                            ->minDate(now())
                            ->native(false)
                            ->live()
                            ->afterStateUpdated($updateTotals),
                        Forms\Components\DatePicker::make('end_date')
                            ->label(__('Fecha de Devolución'))
                            ->required()
                            ->minDate(fn ($get) => $get('start_date') ?? now())
                            ->native(false)
                            ->after(fn (Forms\Get $get) => $get('start_date') ?? now())
                            ->live()
                            ->afterStateUpdated($updateTotals),
                        Forms\Components\Placeholder::make('total_days')
                            ->label(__('Días de Alquiler'))
                            ->content(function (Forms\Get $get) {
                                $start = $get('start_date');
                                $end = $get('end_date');
                                if ($start && $end) {
                                    $diff = \Carbon\Carbon::parse($start)->startOfDay()->diffInDays(\Carbon\Carbon::parse($end)->startOfDay());
                                    $days = max(1, (int) $diff);
                                    return $days . ' ' . ($days === 1 ? __('día') : __('días'));
                                }
                                return '-';
                            }),
                    ])->columns(3),

                Forms\Components\Section::make(__('Precios'))
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->label(__('Subtotal'))
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('discount_amount')
                            ->label(__('Descuento'))
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0)
                            ->live(debounce: 500)
                            ->afterStateUpdated($updateTotals),
                        Forms\Components\TextInput::make('tax_amount')
                            ->label(__('Impuestos'))
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('total_amount')
                            ->label(__('Total a Pagar'))
                            ->required()
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('deposit_amount')
                            ->label(__('Fianza'))
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01),
                    ])->columns(2),

                Forms\Components\Section::make(__('Estado'))
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label(__('Estado de la Reserva'))
                            ->required()
                            ->options([
                                'pending' => __('Pendiente'),
                                'confirmed' => __('Confirmada'),
                                'active' => __('Activa'),
                                'completed' => __('Completada'),
                                'cancelled' => __('Cancelada'),
                            ])
                            ->default('pending')
                            ->reactive(),
                        Forms\Components\Select::make('payment_status')
                            ->label(__('Estado del Pago'))
                            ->required()
                            ->options([
                                'unpaid' => __('No pagado'),
                                'partial' => __('Pago parcial'),
                                'paid' => __('Pagado'),
                                'refunded' => __('Reembolsado'),
                            ])
                            ->default('unpaid'),
                        Forms\Components\Textarea::make('notes')
                            ->label(__('Notas'))
                            ->maxLength(1000)
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_number')
                    ->label(__('Localizador'))
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyable(fn ($state) => (bool) $state),
                Tables\Columns\TextColumn::make('customer.first_name')
                    ->label(__('Cliente'))
                    ->formatStateUsing(fn ($record) => $record->customer?->full_name)
                    ->description(fn ($record) => $record->customer?->email)
                    ->searchable(['first_name', 'last_name', 'email', 'nif_cif'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('vehicle.name')
                    ->label(__('Vehículo'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label(__('Recogida'))
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label(__('Devolución'))
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration_days')
                    ->label(__('Días'))
                    ->formatStateUsing(fn ($record) => $record->start_date && $record->end_date ? $record->start_date->diffInDays($record->end_date) : 0)
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label(__('Estado'))
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'primary' => 'active',
                        'secondary' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label(__('Pago'))
                    ->colors([
                        'danger' => 'unpaid',
                        'warning' => 'partial',
                        'success' => 'paid',
                        'secondary' => 'refunded',
                    ]),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label(__('Total'))
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Creada el'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('Estado'))
                    ->options([
                        'pending' => __('Pendiente'),
                        'confirmed' => __('Confirmada'),
                        'active' => __('Activa'),
                        'completed' => __('Completada'),
                        'cancelled' => __('Cancelada'),
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label(__('Pago'))
                    ->options([
                        'unpaid' => __('No pagado'),
                        'partial' => __('Pago parcial'),
                        'paid' => __('Pagado'),
                        'refunded' => __('Reembolsado'),
                    ]),
                Tables\Filters\SelectFilter::make('customer_id')
                    ->label(__('Cliente'))
                    ->relationship('customer', 'first_name')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('vehicle_id')
                    ->label(__('Vehículo'))
                    ->relationship('vehicle', 'name')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('start_date')
                    ->label(__('Reservas desde'))
                    ->options(function () {
                        return \App\Models\Booking::whereNotNull('start_date')
                            ->pluck('start_date', 'id')
                            ->mapWithKeys(fn($date, $id) => [$id => $date->format('d/m/Y H:i')])
                            ->toArray();
                    }),
                Tables\Filters\SelectFilter::make('end_date')
                    ->label(__('Reservas hasta')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('confirmBooking')
                        ->label(__('Confirmar Seleccionadas'))
                        ->action(fn ($records) => $records->where('status', 'pending')->each->update(['status' => 'confirmed']))
                        ->icon('heroicon-o-check-circle')
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('cancelBooking')
                        ->label(__('Cancelar Seleccionadas'))
                        ->action(fn ($records) => $records->whereNotIn('status', ['completed', 'cancelled'])->each->update(['status' => 'cancelled']))
                        ->icon('heroicon-o-x-circle')
                        ->requiresConfirmation()
                        ->modalHeading(__('Cancelar Reservas'))
                        ->modalDescription(__('¿Estás seguro de querer cancelar las reservas seleccionadas? Esta acción no se puede deshacer.')),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            PaymentsRelationManager::class,
            InvoiceRelationManager::class,
            ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __(static::$navigationGroup);
    }

    public static function getModelLabel(): string
    {
        return __(static::$modelLabel ?? \Illuminate\Support\Str::headline(class_basename(static::$model)));
    }

    public static function getPluralModelLabel(): string
    {
        return __(static::$pluralModelLabel ?? \Illuminate\Support\Str::plural(static::getModelLabel()));
    }
}

