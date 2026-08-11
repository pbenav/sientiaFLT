<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-euro';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?string $modelLabel = 'Factura';
    protected static ?string $pluralModelLabel = 'Facturas';

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false; // Invoices are immutable for compliance
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false; // Invoices cannot be deleted, must be rectified
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Detalles de Factura'))
                    ->schema([
                        Forms\Components\TextInput::make('invoice_number')
                            ->label(__('Número de Factura'))
                            ->required()
                            ->maxLength(50),
                        Forms\Components\Select::make('booking_id')
                            ->label(__('Reserva Relacionada'))
                            ->relationship('booking', 'booking_number')
                            ->searchable()
                            ->nullable(),
                        Forms\Components\Select::make('customer_id')
                            ->label(__('Cliente'))
                            ->relationship('customer', 'first_name')
                            ->searchable()
                            ->nullable(),
                        Forms\Components\Select::make('type')
                            ->label(__('Tipo de Documento'))
                            ->options([
                                'factura' => 'Factura Estándar',
                                'abono' => 'Factura Rectificativa (Abono)',
                                'proforma' => 'Proforma',
                            ])
                            ->required()
                            ->default('factura'),
                    ])->columns(2),

                Forms\Components\Section::make(__('Fechas y Estados'))
                    ->schema([
                        Forms\Components\DatePicker::make('issue_date')
                            ->label(__('Fecha de Emisión'))
                            ->default(now())
                            ->required(),
                        Forms\Components\DatePicker::make('due_date')
                            ->label(__('Fecha de Vencimiento'))
                            ->default(now()),
                        Forms\Components\Select::make('status')
                            ->label(__('Estado de la Factura'))
                            ->options([
                                'draft' => 'Borrador',
                                'sent' => 'Enviada',
                                'paid' => 'Cobrada',
                                'overdue' => 'Vencida',
                                'cancelled' => 'Anulada',
                            ])
                            ->required()
                            ->default('draft'),
                    ])->columns(3),

                Forms\Components\Section::make(__('Importes'))
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->label(__('Base Imponible'))
                            ->numeric()
                            ->prefix('€'),
                        Forms\Components\TextInput::make('tax_amount')
                            ->label(__('Impuestos (IVA)'))
                            ->numeric()
                            ->prefix('€'),
                        Forms\Components\TextInput::make('total_amount')
                            ->label(__('Total'))
                            ->numeric()
                            ->required()
                            ->prefix('€'),
                        Forms\Components\TextInput::make('currency_code')
                            ->label(__('Moneda'))
                            ->default('EUR')
                            ->maxLength(3),
                    ])->columns(4),
                
                Forms\Components\Section::make(__('Otros'))
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label(__('Notas Internas'))
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label(__('Número'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.first_name')
                    ->label(__('Cliente'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking.booking_number')
                    ->label(__('Reserva'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('Tipo'))
                    ->badge()
                    ->colors([
                        'primary' => 'factura',
                        'warning' => 'abono',
                        'secondary' => 'proforma',
                    ]),
                Tables\Columns\TextColumn::make('issue_date')
                    ->label(__('Fecha Emisión'))
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label(__('Total'))
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label(__('Estado'))
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Borrador',
                        'sent' => 'Enviada',
                        'paid' => 'Pagada',
                        'overdue' => 'Vencida',
                        'cancelled' => 'Cancelada',
                        default => $state,
                    })
                    ->colors([
                        'secondary' => 'draft',
                        'info' => 'sent',
                        'success' => 'paid',
                        'danger' => 'overdue',
                        'danger' => 'cancelled',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('Estado'))
                    ->options([
                        'draft' => 'Borrador',
                        'sent' => 'Enviada',
                        'paid' => 'Cobrada',
                        'overdue' => 'Vencida',
                        'cancelled' => 'Anulada',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('Tipo'))
                    ->options([
                        'factura' => 'Factura',
                        'abono' => 'Abono',
                        'proforma' => 'Proforma',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('sign_autofirma')
                    ->label(__('Firmar (AutoFirma)'))
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->url(fn (\App\Models\Invoice $record) => route('autofirma.sign', ['invoice' => $record->id]))
                    ->visible(fn (\App\Models\Invoice $record) => $record->status !== 'paid' && empty($record->pdf_path)),
                Tables\Actions\Action::make('print_factura')
                    ->label(__('Imprimir'))
                    ->icon('heroicon-o-printer')
                    ->url(fn (\App\Models\Invoice $record) => route('pdf.factura', ['invoice' => $record->id]))
                    ->openUrlInNewTab(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->defaultSort('issue_date', 'desc');
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
