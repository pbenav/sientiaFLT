<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PricePeriodResource\Pages;
use App\Models\PricePeriod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PricePeriodResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Vehículos';

    protected static ?string $navigationLabel = 'Períodos de Precio';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Período de Precio')
                    ->description('Define un período de fechas con su precio base por día')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ej: Bajo (Enero-Mayo)')
                            ->columnSpanFull(),

                        Forms\Components\Fieldset::make('Fechas')
                            ->schema([
                                Forms\Components\DatePicker::make('start_date')
                                    ->label('Fecha de inicio')
                                    ->required()
                                    ->native(false),

                                Forms\Components\DatePicker::make('end_date')
                                    ->label('Fecha de fin')
                                    ->required()
                                    ->native(false),
                            ]),

                        Forms\Components\TextInput::make('base_price')
                            ->label('Precio base por día (€)')
                            ->required()
                            ->numeric()
                            ->prefix('€')
                            ->default(0)
                            ->step(0.01),

                        Forms\Components\Toggle::make('active')
                            ->label('Activo')
                            ->default(true),
                    ])
                    ->columns(2),
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

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Fin')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('base_price')
                    ->label('Precio/día')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2, ',', '.') . ' €/día'),

                Tables\Columns\IconColumn::make('active')
                    ->label('Activo')
                    ->boolean()
                    ->trueColor('green')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Estado')
                    ->boolean()
                    ->trueLabel('Activos')
                    ->falseLabel('Inactivos')
                    ->native(false),
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
                    Tables\Actions\BulkAction::make('toggleActive')
                        ->label('Activar/Desactivar')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['active' => !$record->active]);
                            }
                        })
                        ->icon(fn ($records) => $records && count($records) > 0 && $records[0]->active ? 'heroicon-o-ban' : 'heroicon-o-check-circle')
                        ->requiresConfirmation(),
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
            'index' => Pages\ListPricePeriods::route('/'),
            'create' => Pages\CreatePricePeriod::route('/create'),
            'edit' => Pages\EditPricePeriod::route('/{record}/edit'),
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

