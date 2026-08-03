<?php

namespace App\Filament\Resources\VehicleCategoryResource\Pages;

use App\Filament\Resources\VehicleCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVehicleCategory extends \App\Filament\Resources\Pages\BaseEditRecord
{
    protected static string $resource = VehicleCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    }
