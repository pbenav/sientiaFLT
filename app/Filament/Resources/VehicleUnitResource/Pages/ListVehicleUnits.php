<?php

namespace App\Filament\Resources\VehicleUnitResource\Pages;

use App\Filament\Resources\VehicleUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVehicleUnits extends ListRecords
{
    protected static string $resource = VehicleUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
