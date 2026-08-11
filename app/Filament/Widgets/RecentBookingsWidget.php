<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RecentBookingsWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Booking::with(['customer', 'vehicle'])
                ->orderByDesc('created_at')
                ->limit(10))
            ->columns([
                TextColumn::make('booking_number')
                    ->label('Nº Reserva/Ticket')
                    ->sortable(),
                TextColumn::make('customer.first_name')
                    ->label('Cliente')
                    ->formatStateUsing(fn ($record) => $record->customer?->full_name ?? 'Consumidor Final')
                    ->searchable(['first_name', 'last_name', 'email']),
                TextColumn::make('vehicle.name')
                    ->label('Vehículo'),
                TextColumn::make('booking_source')
                    ->label('Origen')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pos' => 'success',
                        'web' => 'primary',
                        default => 'gray',
                    }),
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('EUR')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'confirmed',
                        'success' => 'active',
                        'gray' => 'completed',
                        'danger' => 'cancelled',
                        'danger' => 'no_show',
                    ]),
            ]);
    }
}
