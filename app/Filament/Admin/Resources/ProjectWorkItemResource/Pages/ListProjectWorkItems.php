<?php

namespace App\Filament\Admin\Resources\ProjectWorkItemResource\Pages;

use App\Filament\Admin\Resources\ProjectWorkItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListProjectWorkItems extends ListRecords
{
    protected static string $resource = ProjectWorkItemResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [
            Actions\CreateAction::make(),
        ];

        // Check if accessed from Project list page (via project_id filter)
        if (request()->has('tableFilters.project_id')) {
            $actions[] = Action::make('backToProjects')
                ->label('Kembali ke Daftar Proyek')
                ->url(route('filament.admin.resources.projects.index'))
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('primary');
        }

        return $actions;
    }
} 