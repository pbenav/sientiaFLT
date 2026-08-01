<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make('Contenido de la Página')
                        ->schema([
                            TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (string $operation, $state, SetState $set) => $operation === 'create' ? $set('slug', \Str::slug($state)) : null),

                            TextInput::make('slug')
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true)
                                ->disabled(fn ($record) => $record !== null)
                                ->dehydrated(fn ($record) => $record === null),

                            Textarea::make('excerpt')
                                ->maxLength(500)
                                ->rows(2)
                                ->placeholder('Breve descripción para SEO y previews'),

                            MarkdownEditor::make('content')
                                ->required()
                                ->columnSpan('full')
                                ->fileAttachmentsDirectory('pages'),
                        ])
                        ->columnSpan(2),

                    Section::make('Publicación')
                        ->schema([
                            ToggleButtons::make('status')
                                ->required()
                                ->inline()
                                ->options([
                                    'draft' => 'Borrador',
                                    'published' => 'Publicada',
                                ])
                                ->colors([
                                    'draft' => 'gray',
                                    'published' => 'success',
                                ])
                                ->default('draft'),

                            Toggle::make('published')
                                ->label('Página publicada')
                                ->helperText('Las páginas publicadas son accesibles públicamente')
                                ->default(false),

                            Toggle::make('in_menu')
                                ->label('Mostrar en menú')
                                ->default(false),

                            TextInput::make('menu_order')
                                ->label('Orden en menú')
                                ->numeric()
                                ->default(0)
                                ->visible(fn ($get) => $get('in_menu')),

                            Select::make('template')
                                ->label('Plantilla')
                                ->options([
                                    'default' => 'Por defecto',
                                    'fullwidth' => 'Ancho completo',
                                    'landing' => 'Landing page',
                                ])
                                ->default('default'),

                            Select::make('layout')
                                ->label('Layout')
                                ->options([
                                    'layouts.app' => 'Layout principal',
                                    'layouts.nofooter' => 'Sin footer',
                                    'layouts.minimal' => 'Minimal',
                                ])
                                ->default('layouts.app'),
                        ])
                        ->columnSpan(1),
                ])->columns(3),

                Section::make('SEO')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Título SEO')
                            ->maxLength(70)
                            ->placeholder('Se usará si se deja vacío'),

                        Textarea::make('meta_description')
                            ->label('Descripción SEO')
                            ->maxLength(160)
                            ->rows(3)
                            ->placeholder('Aparecerá en los resultados de búsqueda'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->searchable(),

                BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'draft',
                        'success' => 'published',
                    ])
                    ->sortable(),

                ToggleColumn::make('published')
                    ->offColor('gray')
                    ->onColor('success'),

                ToggleColumn::make('in_menu')
                    ->offColor('gray')
                    ->onColor('success'),

                TextColumn::make('menu_order')
                    ->label('Orden')
                    ->sortable()
                    ->numeric()
                    ->hidden(),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Borrador',
                        'published' => 'Publicada',
                    ]),
                SelectFilter::make('published')
                    ->options([
                        '0' => 'No publicada',
                        '1' => 'Publicada',
                    ]),
            ])
            ->defaultSort('menu_order', 'asc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
