<?php

namespace App\Filament\Resources\MenuResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Model;
use App\Models\Page;

class MenuItemsManager extends \Filament\Resources\RelationManagers\RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Select::make('type')
                    ->required()
                    ->options([
                        'page' => 'Página',
                        'custom' => 'URL personalizada',
                        'separator' => 'Separador',
                    ])
                    ->live()
                    ->afterStateUpdated(fn ($state, Set $set) => $set('page_id', null) || $set('url', null)),

                Select::make('page_id')
                    ->label('Página')
                    ->relationship('page', 'title')
                    ->searchable()
                    ->placeholder('Seleccionar página...')
                    ->visible(fn ($get) => $get('type') === 'page')
                    ->required(fn ($get) => $get('type') === 'page')
                    ->disabled(fn ($record) => $record?->type === 'separator'),

                TextInput::make('url')
                    ->label('URL')
                    ->placeholder('/mi-pagina o https://ejemplo.com')
                    ->required(fn ($get) => $get('type') === 'custom')
                    ->visible(fn ($get) => $get('type') === 'custom'),

                TextInput::make('target')
                    ->label('Target')
                    ->options([
                        '_self' => 'Misma ventana',
                        '_blank' => 'Nueva ventana',
                    ])
                    ->default('_self')
                    ->visible(fn ($get) => $get('type') !== 'separator'),

                TextInput::make('menu_order')
                    ->label('Orden')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordAction('edit_item')
            ->columns([
                TextColumn::make('menu_order')
                    ->label('Orden')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'primary' => 'page',
                        'warning' => 'custom',
                        'gray' => 'separator',
                    ])
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('page.title')
                    ->label('Página')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('url')
                    ->label('URL')
                    ->searchable()
                    ->toggleable()
                    ->copyable()
                    ->copyMessage('Copiado'),
            ])
            ->reorderable('menu_order')
            ->defaultSort('menu_order', 'asc')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Añadir elemento'),
            ])
            ->actions([
                Tables\Actions\EditAction::make('edit_item')
                    ->label('Editar'),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
