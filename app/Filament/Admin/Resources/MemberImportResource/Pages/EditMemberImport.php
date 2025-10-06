<?php

namespace App\Filament\Admin\Resources\MemberImportResource\Pages;

use App\Filament\Admin\Resources\MemberImportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMemberImport extends EditRecord
{
    protected static string $resource = MemberImportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
