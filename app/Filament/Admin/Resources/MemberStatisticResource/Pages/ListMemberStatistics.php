<?php

namespace App\Filament\Admin\Resources\MemberStatisticResource\Pages;

use App\Filament\Admin\Resources\MemberStatisticResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMemberStatistics extends ListRecords
{
    protected static string $resource = MemberStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
