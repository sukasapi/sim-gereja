<?php

namespace App\Filament\Admin\Resources\RegionTypeResource\Pages;

use App\Filament\Admin\Resources\RegionTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegionTypes extends ListRecords
{
    protected static string $resource = RegionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
