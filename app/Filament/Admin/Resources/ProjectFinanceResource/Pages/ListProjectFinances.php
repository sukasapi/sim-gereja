<?php

namespace App\Filament\Admin\Resources\ProjectFinanceResource\Pages;

use App\Filament\Admin\Resources\ProjectFinanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectFinances extends ListRecords
{
    protected static string $resource = ProjectFinanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
