<?php

namespace App\Filament\Resources\VehicleUnitResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ExpensesRelationManager extends RelationManager
{
    protected static string $relationship = 'expenses';
    protected static ?string $title = 'Historial de Mantenimiento y Gastos';
    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->label('Fecha del Gasto')
                    ->default(now())
                    ->required(),
                Forms\Components\Select::make('type')
                    ->label('Tipo de Gasto')
                    ->options([
                        'maintenance' => 'Mantenimiento Preventivo',
                        'repair' => 'Reparación / Avería',
                        'insurance' => 'Seguro',
                        'tax' => 'Impuestos / ITV',
                        'deposit' => 'Fianza / Aval',
                        'other' => 'Otros',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Importe (€)')
                    ->numeric()
                    ->prefix('€')
                    ->required(),
                Forms\Components\TextInput::make('invoice_number')
                    ->label('Nº Factura / Referencia')
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Descripción / Taller')
                    ->columnSpanFull()
                    ->maxLength(500),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tipo')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'maintenance' => 'Mantenimiento',
                        'repair' => 'Reparación',
                        'insurance' => 'Seguro',
                        'tax' => 'Impuesto/ITV',
                        'deposit' => 'Fianza',
                        'other' => 'Otros',
                        default => $state,
                    })
                    ->colors([
                        'primary' => 'maintenance',
                        'danger' => 'repair',
                        'warning' => 'insurance',
                        'success' => 'tax',
                        'secondary' => 'other',
                    ]),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(30),
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Factura')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Importe')
                    ->money('EUR')
                    ->weight('bold')
                    ->color('danger')
                    ->sortable(),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Filtrar por Tipo')
                    ->options([
                        'maintenance' => 'Mantenimiento',
                        'repair' => 'Reparación',
                        'insurance' => 'Seguro',
                        'tax' => 'Impuesto/ITV',
                        'deposit' => 'Fianza',
                        'other' => 'Otros',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Registrar Gasto'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
