<?php

namespace App\Filament\Resources\TaxResource\Pages;

use App\Filament\Resources\TaxResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTax extends \App\Filament\Resources\Pages\BaseEditRecord
{
    protected static string $resource = TaxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
