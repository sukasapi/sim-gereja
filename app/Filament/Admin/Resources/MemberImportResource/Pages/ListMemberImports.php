<?php

namespace App\Filament\Admin\Resources\MemberImportResource\Pages;

use App\Filament\Admin\Resources\MemberImportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMemberImports extends ListRecords
{
    protected static string $resource = MemberImportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
