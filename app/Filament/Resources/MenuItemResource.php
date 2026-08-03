<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Models\Menu;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('menu_id')
                    ->relationship('menu', 'name')
                    ->required()
                    ->searchable(),

                Select::make('parent_id')
                    ->relationship('parent', 'title')
                    ->searchable()
                    ->placeholder('Raíz (sin padre)'),

                TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Select::make('type')
                    ->required()
                    ->options([
                        'page' => 'Página',
                        'custom' => 'URL personalizada',
                        'separator' => 'Separador',
                    ]),

                Select::make('page_id')
                    ->label('Página')
                    ->relationship('page', 'title')
                    ->searchable()
                    ->placeholder('Seleccionar página...')
                    ->visible(fn ($get) => $get('type') === 'page'),

                TextInput::make('url')
                    ->label('URL')
                    ->placeholder('/mi-pagina o https://ejemplo.com')
                    ->required(fn ($get) => $get('type') === 'custom')
                    ->visible(fn ($get) => $get('type') === 'custom'),

                Select::make('target')
                    ->label('Target')
                    ->options([
                        '_self' => 'Misma ventana',
                        '_blank' => 'Nueva ventana',
                    ])
                    ->default('_self'),

                TextInput::make('menu_order')
                    ->label('Orden')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('menu.name')
                    ->label('Menú')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => __($state))
                    ->colors([
                        'primary' => 'page',
                        'warning' => 'custom',
                        'gray' => 'separator',
                    ])
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('page.title')
                    ->label('Página')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('menu_order')
                    ->label('Orden')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([])
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
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

