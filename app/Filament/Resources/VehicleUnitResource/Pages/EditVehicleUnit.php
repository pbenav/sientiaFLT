<?php

namespace App\Filament\Resources\VehicleUnitResource\Pages;

use App\Filament\Resources\VehicleUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVehicleUnit extends EditRecord
{
    protected static string $resource = VehicleUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
