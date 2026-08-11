<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TicketResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    
    protected static ?string $navigationLabel = 'Tickets TPV';
    
    protected static ?string $modelLabel = 'Ticket de Caja';
    
    protected static ?string $pluralModelLabel = 'Tickets TPV';
    
    protected static ?string $navigationGroup = 'Operaciones';
    
    protected static ?int $navigationSort = 3;

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return $record->invoice === null;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return $record->invoice === null;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('booking_source', 'pos');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Ticket')
                    ->schema([
                        Forms\Components\TextInput::make('booking_number')
                            ->label('Número de Ticket')
                            ->disabled(),
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->first_name} {$record->last_name} ({$record->nif_cif})")
                            ->label('Cliente (Opcional)')
                            ->searchable(),
                        Forms\Components\Select::make('vehicle_id')
                            ->relationship('vehicle', 'name')
                            ->label('Vehículo'),
                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total Cobrado')
                            ->numeric()
                            ->prefix('€'),
                        Forms\Components\Select::make('payment_status')
                            ->label('Estado de Pago')
                            ->options([
                                'unpaid' => 'Pendiente',
                                'paid' => 'Pagado',
                            ]),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking_number')
                    ->label('Ticket #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('customer.first_name')
                    ->label('Cliente')
                    ->formatStateUsing(fn ($record) => $record->customer ? "{$record->customer->first_name} {$record->customer->last_name}" : 'Anónimo')
                    ->searchable(['first_name', 'last_name']),
                Tables\Columns\TextColumn::make('vehicle.name')
                    ->label('Vehículo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('EUR')
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Pago')
                    ->colors([
                        'danger' => 'unpaid',
                        'success' => 'paid',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'unpaid' => 'Pendiente',
                        'paid' => 'Cobrado',
                        default => $state,
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Estado')
                    ->options([
                        'paid' => 'Cobrado',
                        'unpaid' => 'Pendiente',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('print_ticket')
                    ->label(__('Imprimir Ticket'))
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn (Booking $record) => route('pdf.ticket', ['booking' => $record->id]))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // Relación con pagos o facturas si fuera necesario
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
