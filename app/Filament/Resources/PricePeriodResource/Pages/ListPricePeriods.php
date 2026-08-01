<?php

namespace App\Filament\Resources\PricePeriodResource\Pages;

use App\Filament\Resources\PricePeriodResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPricePeriods extends ListRecords
{
    protected static string $resource = PricePeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
