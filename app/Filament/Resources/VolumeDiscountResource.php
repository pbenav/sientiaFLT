<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VolumeDiscountResource\Pages;
use App\Models\VolumeDiscount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VolumeDiscountResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Vehículos';

    protected static ?string $navigationLabel = 'Descuentos por Volumen';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Descuento por Volumen')
                    ->description('Define descuentos según el número de días de alquiler por categoría de vehículo')
                    ->schema([
                        Forms\Components\Select::make('vehicle_category_id')
                            ->label('Categoría')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->required()
                            ->preload(),

                        Forms\Components\Fieldset::make('Rango de días')
                            ->schema([
                                Forms\Components\TextInput::make('min_days')
                                    ->label('Días mínimo')
                                    ->required()
                                    ->numeric()
                                    ->integer()
                                    ->minValue(1)
                                    ->default(1),

                                Forms\Components\TextInput::make('max_days')
                                    ->label('Días máximo (vacío = ilimitado)')
                                    ->numeric()
                                    ->integer()
                                    ->minValue(1)
                                    ->nullable(),
                            ]),

                        Forms\Components\TextInput::make('discount_percent')
                            ->label('Descuento (%)')
                            ->required()
                            ->numeric()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(1)
                            ->default(0),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable()
                    ->weight('font-medium'),

                Tables\Columns\TextColumn::make('min_days')
                    ->label('Desde')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state . ' días'),

                Tables\Columns\TextColumn::make('max_days')
                    ->label('Hasta')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ? $state . ' días' : '∞'),

                Tables\Columns\TextColumn::make('discount_percent')
                    ->label('Descuento')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 20 => 'red',
                        $state >= 10 => 'orange',
                        $state >= 5 => 'yellow',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVolumeDiscounts::route('/'),
            'create' => Pages\CreateVolumeDiscount::route('/create'),
            'edit' => Pages\EditVolumeDiscount::route('/{record}/edit'),
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

