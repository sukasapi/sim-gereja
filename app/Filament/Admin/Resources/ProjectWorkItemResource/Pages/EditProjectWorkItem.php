<?php

namespace App\Filament\Admin\Resources\ProjectWorkItemResource\Pages;

use App\Filament\Admin\Resources\ProjectWorkItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectWorkItem extends EditRecord
{
    protected static string $resource = ProjectWorkItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
} 