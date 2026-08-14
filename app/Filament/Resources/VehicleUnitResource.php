<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleUnitResource\Pages;
use App\Models\VehicleUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class VehicleUnitResource extends Resource
{
    protected static ?string $model = VehicleUnit::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Vehículos';
    protected static ?string $modelLabel = 'Unidad Física';
    protected static ?string $pluralModelLabel = 'Flota Física';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identificación')
                    ->schema([
                        Forms\Components\Select::make('vehicle_id')
                            ->relationship('vehicle', 'name')
                            ->required()
                            ->label('Categoría Vehículo')
                            ->searchable(),
                        Forms\Components\TextInput::make('license_plate')
                            ->label('Matrícula')
                            ->required()
                            ->maxLength(255)
                            ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                            ->dehydrateStateUsing(fn ($state) => $state ? strtoupper($state) : null),
                        Forms\Components\TextInput::make('vin')
                            ->label('Nº Bastidor')
                            ->maxLength(255)
                            ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                            ->dehydrateStateUsing(fn ($state) => $state ? strtoupper($state) : null),
                        Forms\Components\TextInput::make('color')
                            ->label('Color')
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options([
                                'active' => 'Disponible',
                                'maintenance' => 'Mantenimiento',
                                'in_use' => 'Alquilado',
                                'retired' => 'Retirado',
                            ])
                            ->default('active')
                            ->required(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Finanzas y Telemetría')
                    ->schema([
                        Forms\Components\DatePicker::make('purchase_date')
                            ->label('Fecha Compra'),
                        Forms\Components\TextInput::make('purchase_price')
                            ->label('Precio Compra (€)')
                            ->numeric()
                            ->prefix('€'),
                        Forms\Components\TextInput::make('current_km')
                            ->label('Kilometraje Actual')
                            ->numeric()
                            ->suffix('km')
                            ->default(0),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Información Adicional')
                    ->schema([
                        Forms\Components\TagsInput::make('extras')
                            ->label('Equipamiento Extra Físico'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Observaciones')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vehicle.name')
                    ->label('Modelo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('license_plate')
                    ->label('Matrícula')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Disponible',
                        'maintenance' => 'Mantenimiento',
                        'in_use' => 'Alquilado',
                        'retired' => 'Retirado',
                        default => $state,
                    })
                    ->colors([
                        'success' => 'active',
                        'warning' => 'maintenance',
                        'primary' => 'in_use',
                        'danger' => 'retired',
                    ]),
                Tables\Columns\TextColumn::make('current_km')
                    ->label('Kilómetros')
                    ->numeric()
                    ->suffix(' km')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amortization_progress')
                    ->label('Amortización')
                    ->formatStateUsing(fn ($record) => number_format($record->amortization_progress, 1) . '%')
                    ->color(fn ($record) => $record->amortization_progress >= 100 ? 'success' : 'warning')
                    ->badge(),
                Tables\Columns\TextColumn::make('roi')
                    ->label('Beneficio (ROI)')
                    ->money('EUR')
                    ->color(fn ($record) => $record->roi >= 0 ? 'success' : 'danger')
                    ->weight('bold'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'active' => 'Disponible',
                        'maintenance' => 'Mantenimiento',
                        'in_use' => 'Alquilado',
                        'retired' => 'Retirado',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Panel Financiero y Analítico de la Unidad')
                    ->description('Estadísticas calculadas en tiempo real a partir de todo el histórico de reservas y alquileres de esta matrícula concreta.')
                    ->schema([
                        Infolists\Components\Grid::make(4)
                            ->schema([
                                Infolists\Components\TextEntry::make('total_revenue')
                                    ->label('Ingresos Brutos')
                                    ->money('EUR')
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->weight('bold')
                                    ->color('success'),
                                Infolists\Components\TextEntry::make('purchase_price')
                                    ->label('Coste Adquisición')
                                    ->money('EUR')
                                    ->default(0),
                                Infolists\Components\TextEntry::make('maintenance_cost')
                                    ->label('Gasto Reparaciones')
                                    ->money('EUR')
                                    ->color('danger')
                                    ->default(0),
                                Infolists\Components\TextEntry::make('roi')
                                    ->label('Retorno (ROI)')
                                    ->money('EUR')
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->weight('bold')
                                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger'),
                            ]),
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('amortization_progress')
                                    ->label('Progreso Amortización')
                                    ->formatStateUsing(fn ($state) => number_format($state, 1) . '% completado')
                                    ->badge()
                                    ->color(fn ($state) => $state >= 100 ? 'success' : 'warning'),
                                Infolists\Components\TextEntry::make('total_days_rented')
                                    ->label('Días Totales en Alquiler'),
                                Infolists\Components\TextEntry::make('utilization_rate')
                                    ->label('Tasa de Ocupación Histórica')
                                    ->formatStateUsing(fn ($state) => number_format($state, 1) . '%')
                                    ->color('info'),
                            ])->columns(3),
                    ]),

                Infolists\Components\Section::make('Datos Técnicos del Vehículo')
                    ->schema([
                        Infolists\Components\TextEntry::make('vehicle.name')->label('Modelo Genérico'),
                        Infolists\Components\TextEntry::make('license_plate')->label('Matrícula')->weight('bold'),
                        Infolists\Components\TextEntry::make('vin')->label('Nº Bastidor'),
                        Infolists\Components\TextEntry::make('color')->label('Color Exterior'),
                        Infolists\Components\TextEntry::make('current_km')->label('Kilometraje Actual')->suffix(' km'),
                        Infolists\Components\TextEntry::make('purchase_date')->label('Fecha de Matriculación/Compra')->date('d/m/Y'),
                    ])->columns(3),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            VehicleUnitResource\RelationManagers\ExpensesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicleUnits::route('/'),
            'create' => Pages\CreateVehicleUnit::route('/create'),
            'view' => Pages\ViewVehicleUnit::route('/{record}'),
            'edit' => Pages\EditVehicleUnit::route('/{record}/edit'),
        ];
    }
}
