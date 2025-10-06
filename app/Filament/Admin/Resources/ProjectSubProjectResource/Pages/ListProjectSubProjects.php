<?php

namespace App\Filament\Admin\Resources\ProjectSubProjectResource\Pages;

use App\Filament\Admin\Resources\ProjectSubProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjectSubProjects extends ListRecords
{
    protected static string $resource = ProjectSubProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
