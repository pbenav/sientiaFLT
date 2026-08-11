<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    protected static ?string $title = 'Reseñas';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('Reseñas');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('rating')
                    ->label(__('Puntuación'))
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(5)
                    ->default(5),
                Forms\Components\TextInput::make('title')
                    ->label(__('Título de la reseña'))
                    ->maxLength(255),
                Forms\Components\Textarea::make('comment')
                    ->label(__('Comentario'))
                    ->rows(4),
                Forms\Components\FileUpload::make('images')
                    ->label(__('Fotos del cliente'))
                    ->image()
                    ->multiple()
                    ->directory('reviews')
                    ->maxFiles(5),
                Forms\Components\Toggle::make('is_approved')
                    ->label(__('Aprobada'))
                    ->default(false),
                Forms\Components\Toggle::make('is_visible')
                    ->label(__('Visible públicamente'))
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rating')
                    ->label(__('Puntos'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => str_repeat('⭐', $state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Título'))
                    ->limit(30),
                Tables\Columns\IconColumn::make('is_approved')
                    ->boolean()
                    ->label(__('Aprobada')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Fecha'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label(__('Estado de Aprobación'))
                    ->placeholder(__('Todas'))
                    ->trueLabel(__('Aprobadas'))
                    ->falseLabel(__('No Aprobadas')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
