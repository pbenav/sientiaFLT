<?php

namespace App\Filament\Pages;

use App\Models\SeoSetting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Actions\Action;

class ManageSeoSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static string $view = 'filament.pages.manage-seo-settings';

    protected static ?string $navigationGroup = 'Configuración';

    protected static ?string $navigationLabel = 'Ajustes SEO';

    protected static ?string $title = 'Ajustes SEO Globales';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = SeoSetting::firstOrCreate([
            'id' => 1,
        ]);

        $this->form->fill($settings->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información Principal')
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Nombre del Sitio')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('meta_title')
                            ->label('Título SEO por Defecto')
                            ->maxLength(255)
                            ->helperText('Se usará si una página no tiene su propio título.'),
                        Textarea::make('meta_description')
                            ->label('Descripción SEO por Defecto')
                            ->maxLength(255)
                            ->rows(3)
                            ->helperText('Se usará si una página no tiene su propia descripción.'),
                        TextInput::make('meta_keywords')
                            ->label('Palabras Clave (Keywords)')
                            ->maxLength(255),
                        \Filament\Forms\Components\FileUpload::make('og_image')
                            ->label('Imagen para Redes Sociales (OpenGraph)')
                            ->image()
                            ->directory('seo')
                            ->columnSpanFull()
                            ->helperText('Se mostrará al compartir enlaces de la web en Facebook, Twitter, WhatsApp, etc.'),
                    ])->columns(2),

                Section::make('Scripts y Analíticas')
                    ->schema([
                        TextInput::make('google_analytics_id')
                            ->label('Google Analytics ID (Ej: G-XXXXXXXXX)')
                            ->maxLength(255),
                        TextInput::make('facebook_pixel_id')
                            ->label('Facebook Pixel ID')
                            ->maxLength(255),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Guardar Cambios')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            $settings = SeoSetting::firstOrCreate(['id' => 1]);
            $settings->update($data);

            Notification::make()
                ->success()
                ->title('Ajustes guardados correctamente')
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error al guardar los ajustes')
                ->body($e->getMessage())
                ->send();
        }
    }
}
