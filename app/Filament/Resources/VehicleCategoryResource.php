<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleCategoryResource\Pages;
use App\Models\CategoryVolumeDiscount;
use App\Models\VehicleCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class VehicleCategoryResource extends Resource
{
    protected static ?string $model = VehicleCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Vehículos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información General')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->label('Nombre')
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->disabled()
                            ->dehydrated(fn ($state) => filled($state)),

                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->columnSpan('full'),

                        Forms\Components\Toggle::make('active')
                            ->label('Activa')
                            ->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Períodos de Precio')
                    ->schema([
                        Forms\Components\Select::make('price_period_ids')
                            ->relationship('pricePeriods', 'name')
                            ->multiple()
                            ->label('Períodos de Precio')
                            ->help('Asigna períodos de precio a esta categoría. Los vehículos de esta categoría heredarán estos precios.'),
                    ]),

                Forms\Components\Section::make('Descuentos por Volumen')
                    ->schema([
                        Forms\Components\Repeater::make('volume_discounts')
                            ->relationship('volumeDiscounts')
                            ->schema([
                                Forms\Components\TextInput::make('min_days')
                                    ->label('Mínimo días')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1),

                                Forms\Components\TextInput::make('max_days')
                                    ->label('Máximo días (vacío = sin límite)')
                                    ->numeric()
                                    ->minValue(1)
                                    ->nullable(),

                                Forms\Components\TextInput::make('discount_percent')
                                    ->label('Descuento %')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100),
                            ])
                            ->columns(3)
                            ->reorderable('sort_order')
                            ->itemLabel(fn (array $state): ?string => $state['min_days'] ? "{$state['min_days']}+ días" : null),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('font-medium'),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),

                Tables\Columns\TextColumn::make('price_periods_count')
                    ->label('Períodos')
                    ->counts('pricePeriods')
                    ->sortable(),

                Tables\Columns\TextColumn::make('volume_discounts_count')
                    ->label('Descuentos')
                    ->counts('volumeDiscounts')
                    ->sortable(),

                Tables\Columns\TextColumn::make('vehicles_count')
                    ->label('Vehículos')
                    ->counts('vehicles')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Activa'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->boolean()
                    ->placeholder('Todos')
                    ->trueLabel('Activas')
                    ->falseLabel('Inactivas'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('toggleActive')
                        ->label('Activar/Desactivar')
                        ->icon('heroicon-o-eye')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['is_active' => !$record->is_active]);
                            }
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicleCategories::route('/'),
            'create' => Pages\CreateVehicleCategory::route('/create'),
            'edit' => Pages\EditVehicleCategory::route('/{record}/edit'),
        ];
    }
}
