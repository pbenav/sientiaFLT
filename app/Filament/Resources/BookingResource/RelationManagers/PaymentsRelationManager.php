<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Pagos';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('Pagos');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('payment_method')
                    ->label(__('Método de Pago'))
                    ->required()
                    ->options([
                        'efectivo' => __('Efectivo'),
                        'tarjeta' => __('Tarjeta de crédito'),
                        'transferencia' => __('Transferencia bancaria'),
                        'stripe' => __('Stripe'),
                        'paypal' => __('PayPal'),
                        'bizum' => __('Bizum'),
                    ])
                    ->reactive(),
                Forms\Components\TextInput::make('amount')
                    ->label(__('Importe'))
                    ->required()
                    ->numeric()
                    ->prefix('€'),
                Forms\Components\Select::make('status')
                    ->label(__('Estado'))
                    ->required()
                    ->options([
                        'pending' => __('Pendiente'),
                        'completed' => __('Completado'),
                        'failed' => 'Fallido',
                        'refunded' => 'Reembolsado',
                    ]),
                Forms\Components\TextInput::make('transaction_id')
                    ->label(__('ID Transacción'))
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payment_method')
                    ->label(__('Método'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'efectivo' => '💵 Efectivo',
                        'tarjeta' => '💳 Tarjeta',
                        'transferencia' => '🏦 Transferencia',
                        'stripe' => '💳 Stripe',
                        'bizum' => '📱 Bizum',
                        'paypal' => '🅿️ PayPal',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('Importe'))
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label(__('Estado'))
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'completed' => 'Completado',
                        'failed' => 'Fallido',
                        'refunded' => 'Reembolsado',
                        default => $state,
                    })
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'failed',
                        'secondary' => 'refunded',
                    ]),
                Tables\Columns\TextColumn::make('transaction_id')
                    ->label(__('ID Trans.'))
                    ->copyable()
                    ->copyable(fn ($state) => (bool) $state)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Fecha'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label(__('Método de Pago'))
                    ->options([
                        'efectivo' => __('Efectivo'),
                        'tarjeta' => __('Tarjeta de crédito'),
                        'transferencia' => __('Transferencia bancaria'),
                        'stripe' => __('Stripe'),
                        'paypal' => __('PayPal'),
                        'bizum' => __('Bizum'),
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('Estado'))
                    ->options([
                        'pending' => __('Pendiente'),
                        'completed' => __('Completado'),
                        'failed' => __('Fallido'),
                        'refunded' => __('Reembolsado'),
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
