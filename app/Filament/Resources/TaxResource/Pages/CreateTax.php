<?php

namespace App\Filament\Resources\TaxResource\Pages;

use App\Filament\Resources\TaxResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTax extends \App\Filament\Resources\Pages\BaseCreateRecord
{
    protected static string $resource = TaxResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
