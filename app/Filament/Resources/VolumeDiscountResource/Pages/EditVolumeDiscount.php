<?php

namespace App\Filament\Resources\VolumeDiscountResource\Pages;

use App\Filament\Resources\VolumeDiscountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVolumeDiscount extends EditRecord
{
    protected static string $resource = VolumeDiscountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
