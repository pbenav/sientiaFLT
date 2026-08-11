<?php

namespace App\Filament\Resources\VehicleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class UnitsRelationManager extends RelationManager
{
    protected static string $relationship = 'units';

    protected static ?string $recordTitleAttribute = 'license_plate';
    
    protected static ?string $title = 'Unidades Físicas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('license_plate')
                    ->label('Matrícula')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('vin')
                    ->label('Nº Bastidor')
                    ->maxLength(255),
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
                Forms\Components\TagsInput::make('extras')
                    ->label('Equipamiento Extra Físico')
                    ->placeholder('Añadir accesorio (ej: Baúl)'),
                Forms\Components\Textarea::make('notes')
                    ->label('Observaciones')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('license_plate')
                    ->label('Matrícula')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('color')
                    ->label('Color'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'maintenance',
                        'primary' => 'in_use',
                        'danger' => 'retired',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Disponible',
                        'maintenance' => 'Mantenimiento',
                        'in_use' => 'Alquilado',
                        'retired' => 'Retirado',
                        default => $state,
                    }),
                Tables\Columns\TagsColumn::make('extras')
                    ->label('Extras'),
            ])
            ->filters([
                //
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
}
