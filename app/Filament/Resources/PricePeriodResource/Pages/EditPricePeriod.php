<?php

namespace App\Filament\Resources\PricePeriodResource\Pages;

use App\Filament\Resources\PricePeriodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPricePeriod extends \App\Filament\Resources\Pages\BaseEditRecord
{
    protected static string $resource = PricePeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
