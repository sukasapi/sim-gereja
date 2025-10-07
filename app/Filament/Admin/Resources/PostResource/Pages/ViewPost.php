<?php

namespace App\Filament\Admin\Resources\PostResource\Pages;

use App\Filament\Admin\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn (): bool => 
                    auth()->user()->isSuperAdmin() || 
                    (auth()->user()->isAdminGereja() && auth()->user()->church_id === $this->record->church_id)
                ),
        ];
    }
}
