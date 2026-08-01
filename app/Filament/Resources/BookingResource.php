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

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Operations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Booking Information')
                    ->schema([
                        Forms\Components\TextInput::make('booking_number')
                            ->required()
                            ->maxLength(50)
                            ->default('BK-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT))
                            ->disabled(),
                        Forms\Components\Select::make('customer_id')
                            ->label('Customer')
                            ->options(Customer::orderBy('first_name')->get()->pluck('full_name', 'id'))
                            ->searchable()
                            ->required()
                            ->reactive(),
                        Forms\Components\Select::make('vehicle_id')
                            ->label('Vehicle')
                            ->options(Vehicle::where('is_active', true)->orderBy('name')->get()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('location_id')
                            ->label('Pick-up/Drop-off Location')
                            ->options(Location::orderBy('name')->get()->pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),
                    ])->columns(2),

                Forms\Components\Section::make('Dates')
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_date')
                            ->required()
                            ->minDate(now())
                            ->native(false),
                        Forms\Components\DateTimePicker::make('end_date')
                            ->required()
                            ->minDate(fn ($get) => $get('start_date') ?? now())
                            ->native(false)
                            ->after(fn (Forms\Get $get) => $get('start_date') ?? now()),
                        Forms\Components\DateTimePicker::make('pick_up_date'),
                        Forms\Components\DateTimePicker::make('drop_off_date'),
                    ])->columns(2),

                Forms\Components\Section::make('Pricing')
                    ->schema([
                        Forms\Components\TextInput::make('total_amount')
                            ->required()
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01),
                        Forms\Components\TextInput::make('deposit_amount')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01),
                        Forms\Components\TextInput::make('discount_amount')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01)
                            ->default(0),
                        Forms\Components\TextInput::make('tax_amount')
                            ->numeric()
                            ->prefix('€')
                            ->step(0.01),
                    ])->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->required()
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'active' => 'Active',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->reactive(),
                        Forms\Components\Select::make('payment_status')
                            ->required()
                            ->options([
                                'unpaid' => 'Unpaid',
                                'partial' => 'Partial',
                                'paid' => 'Paid',
                                'refunded' => 'Refunded',
                            ])
                            ->default('unpaid'),
                        Forms\Components\Textarea::make('notes')
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
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyable(fn ($state) => (bool) $state),
                Tables\Columns\TextColumn::make('customer.first_name')
                    ->label('Customer')
                    ->formatStateUsing(fn ($record) => $record->customer?->full_name)
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('vehicle.name')
                    ->label('Vehicle')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration_days')
                    ->label('Days')
                    ->formatStateUsing(fn ($record) => $record->start_date && $record->end_date ? $record->start_date->diffInDays($record->end_date) : 0)
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'primary' => 'active',
                        'secondary' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'danger' => 'unpaid',
                        'warning' => 'partial',
                        'success' => 'paid',
                        'secondary' => 'refunded',
                    ]),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'partial' => 'Partial',
                        'paid' => 'Paid',
                        'refunded' => 'Refunded',
                    ]),
                Tables\Filters\SelectFilter::make('customer_id')
                    ->relationship('customer', 'first_name')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('vehicle_id')
                    ->relationship('vehicle', 'name')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('start_date')
                    ->label('Booking From')
                    ->options(function () {
                        return \App\Models\Booking::whereNotNull('start_date')
                            ->pluck('start_date', 'id')
                            ->mapWithKeys(fn($date, $id) => [$id => $date->format('d/m/Y H:i')])
                            ->toArray();
                    }),
                Tables\Filters\SelectFilter::make('end_date')
                    ->label('Booking To'),
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
                        ->label('Confirm Selected')
                        ->action(fn ($records) => $records->where('status', 'pending')->each->update(['status' => 'confirmed']))
                        ->icon('heroicon-o-check-circle')
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('cancelBooking')
                        ->label('Cancel Selected')
                        ->action(fn ($records) => $records->whereNotIn('status', ['completed', 'cancelled'])->each->update(['status' => 'cancelled']))
                        ->icon('heroicon-o-x-circle')
                        ->requiresConfirmation()
                        ->modalHeading('Cancel Bookings')
                        ->modalDescription('Are you sure you want to cancel the selected bookings? This action cannot be undone.'),
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

