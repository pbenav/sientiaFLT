<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Marketing & SEO';
    protected static ?string $modelLabel = 'Reseña';
    protected static ?string $pluralModelLabel = 'Reseñas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Datos de la Reseña'))
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->label(__('Cliente (Opcional)'))
                            ->relationship('customer', 'first_name')
                            ->searchable()
                            ->nullable(),
                        Forms\Components\Select::make('vehicle_id')
                            ->label(__('Vehículo Relacionado'))
                            ->relationship('vehicle', 'name')
                            ->searchable()
                            ->nullable(),
                        Forms\Components\Select::make('booking_id')
                            ->label(__('Reserva Relacionada (Opcional)'))
                            ->relationship('booking', 'booking_number')
                            ->searchable()
                            ->nullable(),
                        Forms\Components\TextInput::make('rating')
                            ->label(__('Puntuación (1-5)'))
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->default(5),
                    ])->columns(2),
                
                Forms\Components\Section::make(__('Contenido y SEO'))
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('Título (H1)'))
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\Textarea::make('comment')
                            ->label(__('Historia / Comentario'))
                            ->required()
                            ->rows(6),
                        Forms\Components\FileUpload::make('images')
                            ->label(__('Galería de Fotos (SEO UGC)'))
                            ->image()
                            ->multiple()
                            ->directory('reviews')
                            ->maxFiles(10),
                    ]),
                
                Forms\Components\Section::make(__('Moderación'))
                    ->schema([
                        Forms\Components\Toggle::make('is_approved')
                            ->label(__('Reseña Aprobada'))
                            ->default(true),
                        Forms\Components\Toggle::make('is_visible')
                            ->label(__('Visible Públicamente'))
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.first_name')
                    ->label(__('Cliente'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vehicle.name')
                    ->label(__('Vehículo'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Título'))
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label(__('Valoración'))
                    ->formatStateUsing(fn ($state) => str_repeat('⭐', $state))
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_approved')
                    ->label(__('Aprobada'))
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_visible')
                    ->label(__('Visible'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Fecha'))
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_approved')->label(__('Aprobadas')),
                Tables\Filters\TernaryFilter::make('is_visible')->label(__('Visibles')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
