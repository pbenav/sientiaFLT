<?php

namespace App\Filament\Resources\Pages;

use Filament\Resources\Pages\CreateRecord;

abstract class BaseCreateRecord extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
