<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;

class SettingsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.settings-page';
    
    protected static ?string $navigationLabel = 'Configuración';
    
    protected static ?string $title = 'Configuración de Empresa';
    
    protected static ?string $navigationGroup = 'Configuración';
    
    protected static ?int $navigationSort = 100;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            // General Company Info
            'company_name' => Setting::get('company_name', config('app.name', 'Extrarent')),
            'company_nif' => Setting::get('company_nif', 'B12345678'),
            'company_address' => Setting::get('company_address', 'Calle Ejemplo 1, Madrid'),
            'company_phone' => Setting::get('company_phone', '+34 900 000 000'),
            'company_email' => Setting::get('company_email', 'info@extrarent.com'),

            // PDF Formatting
            'pdf_logo_type' => Setting::get('pdf_logo_type', 'text'),
            'pdf_logo_text' => Setting::get('pdf_logo_text', 'Extrarent Rent-a-Car'),
            'pdf_logo_image' => Setting::get('pdf_logo_image'),
            'currency_symbol' => Setting::get('currency_symbol', '€'),
            'currency_position' => Setting::get('currency_position', 'suffix'),
            
            // Verifactu
            'verifactu_active' => filter_var(Setting::get('verifactu_active', false), FILTER_VALIDATE_BOOLEAN),
            'verifactu_mode' => Setting::get('verifactu_mode', 'test'),
            'verifactu_cert_path' => Setting::get('verifactu_cert_path'),
            'verifactu_cert_password' => Setting::get('verifactu_cert_password'),
            
            // Facturae
            'facturae_active' => filter_var(Setting::get('facturae_active', false), FILTER_VALIDATE_BOOLEAN),
            'facturae_cert_path' => Setting::get('facturae_cert_path'),
            'facturae_cert_password' => Setting::get('facturae_cert_password'),
            
            'contract_clauses' => Setting::get('contract_clauses', "1. El arrendatario asume toda la responsabilidad sobre el vehículo...\n2. Devolución con depósito lleno..."),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('SettingsTabs')
                    ->tabs([
                        Tabs\Tab::make('Datos Fiscales')
                            ->icon('heroicon-o-building-office-2')
                            ->schema([
                                Section::make('Información de la Empresa')
                                    ->schema([
                                        TextInput::make('company_name')->label('Razón Social / Nombre Comercial')->required()->columnSpanFull(),
                                        TextInput::make('company_nif')->label('NIF/CIF')->required(),
                                        TextInput::make('company_phone')->label('Teléfono Principal'),
                                        TextInput::make('company_email')->label('Email de Contacto')->email()->columnSpanFull(),
                                        Textarea::make('company_address')->label('Dirección Fiscal')->rows(2)->columnSpanFull(),
                                    ])->columns(2),
                            ]),

                        Tabs\Tab::make('Formato Documentos')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make('Identidad en PDF (Tickets y Facturas)')
                                    ->schema([
                                        Radio::make('pdf_logo_type')
                                            ->label('Tipo de Logo')
                                            ->options(['text' => 'Texto', 'image' => 'Imagen'])
                                            ->live()
                                            ->columnSpanFull(),
                                        TextInput::make('pdf_logo_text')
                                            ->label('Texto del Logo')
                                            ->visible(fn($get) => $get('pdf_logo_type') === 'text')
                                            ->columnSpanFull(),
                                        FileUpload::make('pdf_logo_image')
                                            ->label('Imagen Logo')
                                            ->image()
                                            ->directory('logos')
                                            ->visible(fn($get) => $get('pdf_logo_type') === 'image')
                                            ->columnSpanFull(),
                                        TextInput::make('currency_symbol')->label('Símbolo de Moneda')->default('€'),
                                        Select::make('currency_position')->label('Posición de Moneda')->options(['suffix' => 'Sufijo (ej. 10€)', 'prefix' => 'Prefijo (ej. €10)'])->default('suffix'),
                                    ])->columns(2),
                            ]),

                        Tabs\Tab::make('Veri*Factu (AEAT)')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Section::make('Configuración de Suministro de Facturas')
                                    ->description('Parámetros para la comunicación automática con la sede electrónica de la Agencia Tributaria.')
                                    ->schema([
                                        Toggle::make('verifactu_active')
                                            ->label('Activar Envío a Veri*Factu')
                                            ->helperText('Habilita el envío automático en segundo plano al cobrar en el TPV.')
                                            ->default(false),
                                        Select::make('verifactu_mode')->label('Entorno')->options(['test' => 'PRUEBAS', 'production' => 'PRODUCCIÓN']),
                                        FileUpload::make('verifactu_cert_path')
                                            ->label('Certificado Digital (.p12 / .pfx)')
                                            ->directory('certificates')
                                            ->disk('local')
                                            ->visibility('private')
                                            ->helperText('Certificado de la empresa o apoderado para firmar el XML.'),
                                        TextInput::make('verifactu_cert_password')
                                            ->label('Contraseña del Certificado')
                                            ->password()
                                            ->revealable(),
                                    ])->columns(2),
                            ]),
                        
                        Tabs\Tab::make('FacturaE (B2G/B2B)')
                            ->icon('heroicon-o-globe-europe-africa')
                            ->schema([
                                Section::make('Facturación Electrónica FACe')
                                    ->description('Generación de XML bajo el estándar FacturaE 3.2.x.')
                                    ->schema([
                                        Toggle::make('facturae_active')
                                            ->label('Activar Soporte FacturaE')
                                            ->default(false),
                                        FileUpload::make('facturae_cert_path')
                                            ->label('Certificado Digital (.p12)')
                                            ->disk('local')
                                            ->directory('certificates')
                                            ->visibility('private')
                                            ->helperText('Si se deja vacío, se utilizará el de Veri*Factu.'),
                                        TextInput::make('facturae_cert_password')
                                            ->label('Contraseña del Certificado')
                                            ->password()
                                            ->revealable(),
                                    ])->columns(2),
                            ]),
                    
                        Tabs\Tab::make('Contrato de Alquiler')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                RichEditor::make('contract_clauses')
                                    ->label('Cláusulas por defecto del contrato de alquiler')
                                    ->helperText('Estas cláusulas se insertarán automáticamente en la impresión del contrato de alquiler que firma el cliente.')
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan('full')
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            $valToSave = is_array($value) ? (count($value) > 0 ? reset($value) : null) : $value;
            Setting::set($key, $valToSave);
        }
        
        Notification::make()->title('Configuración guardada con éxito')->success()->send();
    }
}
