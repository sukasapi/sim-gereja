<?php

namespace App\Filament\Admin\Resources\ProjectWorkSubItemResource\Pages;

use App\Filament\Admin\Resources\ProjectWorkSubItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectWorkSubItem extends EditRecord
{
    protected static string $resource = ProjectWorkSubItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
} 