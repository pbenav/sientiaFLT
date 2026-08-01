<?php

namespace App\Filament\Resources\VolumeDiscountResource\Pages;

use App\Filament\Resources\VolumeDiscountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVolumeDiscounts extends ListRecords
{
    protected static string $resource = VolumeDiscountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
