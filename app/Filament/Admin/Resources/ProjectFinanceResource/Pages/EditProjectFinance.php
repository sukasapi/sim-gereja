<?php

namespace App\Filament\Admin\Resources\ProjectFinanceResource\Pages;

use App\Filament\Admin\Resources\ProjectFinanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectFinance extends EditRecord
{
    protected static string $resource = ProjectFinanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
