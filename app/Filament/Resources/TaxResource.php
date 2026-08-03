<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaxResource\Pages;
use App\Filament\Resources\TaxResource\RelationManagers;
use App\Models\Tax;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaxResource extends Resource
{
    protected static ?string $modelLabel = 'Impuesto';
    protected static ?string $pluralModelLabel = 'Impuestos';

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    
    protected static ?string $navigationGroup = 'Configuración';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Configuración de Impuesto')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre (Ej. IVA 21%)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('rate')
                            ->label('Porcentaje (%)')
                            ->required()
                            ->numeric()
                            ->step(0.01)
                            ->suffix('%'),
                        Forms\Components\TextInput::make('country_code')
                            ->label('Código de País')
                            ->maxLength(2)
                            ->default('ES'),
                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true)
                            ->required(),
                        Forms\Components\Toggle::make('is_default')
                            ->label('Impuesto por Defecto')
                            ->default(false)
                            ->required(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rate')
                    ->label('Porcentaje')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_default')
                    ->label('Por Defecto')
                    ->boolean(),
                Tables\Columns\TextColumn::make('country_code')
                    ->label('País')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTaxes::route('/'),
            'create' => Pages\CreateTax::route('/create'),
            'edit' => Pages\EditTax::route('/{record}/edit'),
        ];
    }
}
