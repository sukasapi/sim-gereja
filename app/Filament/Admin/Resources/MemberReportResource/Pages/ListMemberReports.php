<?php

namespace App\Filament\Admin\Resources\MemberReportResource\Pages;

use App\Filament\Admin\Resources\MemberReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMemberReports extends ListRecords
{
    protected static string $resource = MemberReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
