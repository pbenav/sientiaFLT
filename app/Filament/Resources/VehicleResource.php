<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Filament\Resources\VehicleResource\RelationManagers\BookingsRelationManager;
use App\Filament\Resources\VehicleResource\RelationManagers\VehicleImagesRelationManager;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Vehículos';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información Básica')
                    ->description('Datos principales del vehículo')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set, string $operation) {
                                if ($operation === 'create' || ! $set('slug')) {
                                    $set('slug', Str::slug($state));
                                }
                            })
                            ->helperText('El slug se genera automáticamente'),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique('vehicles', 'slug', ignoreRecord: true)
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Se genera automáticamente desde el nombre'),

                        Forms\Components\TextInput::make('brand')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Ej: SYM, Piaggio, Vespa'),

                        Forms\Components\TextInput::make('model')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Ej: Symphony, Medley, Primavera'),

                        Forms\Components\TextInput::make('license_plate')
                            ->maxLength(50)
                            ->placeholder('Opcional - Matrícula del vehículo'),

                        Forms\Components\TextInput::make('year')
                            ->maxLength(10)
                            ->placeholder('Ej: 2024'),
                    ])->columns(3),

                Forms\Components\Section::make('Categoría y Ubicación')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Categoría')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('La categoría define los períodos de precio y descuentos que aplican a este vehículo')
                            ->required(),

                        Forms\Components\Select::make('location_id')
                            ->relationship('location', 'name')
                            ->searchable()
                            ->nullable()
                            ->helperText('Ubicación principal de este vehículo'),
                    ]),

                Forms\Components\Section::make('Especificaciones')
                    ->description('Características técnicas del vehículo (opcionales)')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options([
                                'scooter' => 'Scooter',
                                'compact' => 'Compacto',
                                'suv' => 'SUV',
                                'sedan' => 'Sedán',
                                'van' => 'Furgoneta',
                                'truck' => 'Camión',
                                'coupe' => 'Cupé',
                                'hatchback' => 'Hatchback',
                            ])
                            ->columnSpan(2),

                        Forms\Components\Select::make('body_type')
                            ->options([
                                'hatchback' => 'Hatchback',
                                'sedan' => 'Sedán',
                                'suv' => 'SUV',
                                'van' => 'Furgoneta',
                                'pickup' => 'Pickup',
                                'coupe' => 'Cupé',
                                'convertible' => 'Convertible',
                            ]),

                        Forms\Components\Select::make('fuel_type')
                            ->options([
                                'gasoline' => 'Gasolina',
                                'diesel' => 'Diésel',
                                'electric' => 'Eléctrico',
                                'hybrid' => 'Híbrido',
                                'plug_in_hybrid' => 'Híbrido Enchufable',
                            ]),

                        Forms\Components\Select::make('transmission')
                            ->options([
                                'automatic' => 'Automática',
                                'manual' => 'Manual',
                                'semi_automatic' => 'Semi-Automática',
                            ]),

                        Forms\Components\TextInput::make('seats')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(20)
                            ->placeholder('Opcional'),

                        Forms\Components\TextInput::make('power_hp')
                            ->maxLength(20)
                            ->placeholder('Ej: 15'),

                        Forms\Components\TextInput::make('engine')
                            ->maxLength(100)
                            ->placeholder('Ej: 125cc'),

                        Forms\Components\TextInput::make('color')
                            ->maxLength(50)
                            ->placeholder('Opcional'),

                        Forms\Components\TextInput::make('doors')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(8),

                        Forms\Components\TextInput::make('luggage_large')
                            ->numeric()
                            ->minValue(0),

                        Forms\Components\TextInput::make('luggage_small')
                            ->numeric()
                            ->minValue(0),
                    ])->columns(3),

                Forms\Components\Section::make('Estado y Visibilidad')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Activo')
                            ->required()
                            ->columnSpan(2),

                        Forms\Components\Toggle::make('is_available')
                            ->label('Disponible')
                            ->required(),

                        Forms\Components\Toggle::make('show_on_homepage')
                            ->label('Mostrar en Inicio'),

                        Forms\Components\Toggle::make('is_new')
                            ->label('Nuevo'),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Destacado'),

                        Forms\Components\Toggle::make('is_eco')
                            ->label('Eco'),

                        Forms\Components\Toggle::make('is_electric')
                            ->label('Eléctrico'),

                        Forms\Components\Toggle::make('is_hybrid')
                            ->label('Híbrido'),
                    ])->columns(4),

                Forms\Components\Section::make('Descripción')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->maxLength(2000)
                            ->rows(4)
                            ->helperText('Descripción corta del vehículo para mostrar en la web'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Vehículo')
                    ->searchable()
                    ->sortable()
                    ->weight('font-medium'),

                Tables\Columns\TextColumn::make('brand')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('model')
                    ->searchable(),

                Tables\Columns\TextColumn::make('license_plate')
                    ->label('Matrícula')
                    ->searchable()
                    ->badge()
                    ->copyable(fn ($state) => (bool) $state)
                    ->copyMessage('Copiado')
                    ->copyMessageDuration('1500'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'scooter' => 'Scooter',
                        'compact' => 'Compacto',
                        'suv' => 'SUV',
                        'sedan' => 'Sedán',
                        'van' => 'Furgoneta',
                        'truck' => 'Camión',
                        default => ucfirst($state ?? ''),
                    })
                    ->color(fn ($state) => match ($state) {
                        'scooter' => 'blue',
                        'compact' => 'blue',
                        'suv' => 'green',
                        'sedan' => 'purple',
                        'van' => 'yellow',
                        'truck' => 'red',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('category.pricePeriods.0.base_price')
                    ->label('Precio base/día')
                    ->formatStateUsing(function ($state, $record) {
                        if (! $record->category) return '—';
                        $currentPrice = $record->getCurrentBasePrice();
                        return number_format($currentPrice, 2, ',', '.') . ' €';
                    })
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean()
                    ->trueColor('green')
                    ->falseColor('gray'),

                Tables\Columns\IconColumn::make('is_available')
                    ->label('Disponible')
                    ->boolean()
                    ->trueColor('green')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'scooter' => 'Scooter',
                        'compact' => 'Compacto',
                        'suv' => 'SUV',
                        'sedan' => 'Sedán',
                        'van' => 'Furgoneta',
                        'truck' => 'Camión',
                        'coupe' => 'Cupé',
                        'hatchback' => 'Hatchback',
                    ]),
                Tables\Filters\SelectFilter::make('fuel_type')
                    ->label('Combustible')
                    ->options([
                        'gasoline' => 'Gasolina',
                        'diesel' => 'Diésel',
                        'electric' => 'Eléctrico',
                        'hybrid' => 'Híbrido',
                        'plug_in_hybrid' => 'Híbrido Enchufable',
                    ]),
                Tables\Filters\SelectFilter::make('transmission')
                    ->label('Transmisión')
                    ->options([
                        'automatic' => 'Automática',
                        'manual' => 'Manual',
                        'semi_automatic' => 'Semi-Automática',
                    ]),
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Categoría'),
                Tables\Filters\SelectFilter::make('location_id')
                    ->relationship('location', 'name')
                    ->label('Ubicación'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Estado Activo'),
                Tables\Filters\TernaryFilter::make('is_available')
                    ->label('Disponibilidad'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('toggleAvailability')
                        ->label('Activar/Desactivar')
                        ->action(fn ($records) => $records->each(fn ($vehicle) => $vehicle->update(['is_available' => !$vehicle->is_available])))
                        ->icon('heroicon-o-eye'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BookingsRelationManager::class,
            VehicleImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}
