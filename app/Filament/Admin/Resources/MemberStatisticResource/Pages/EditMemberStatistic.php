<?php

namespace App\Filament\Admin\Resources\MemberStatisticResource\Pages;

use App\Filament\Admin\Resources\MemberStatisticResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMemberStatistic extends EditRecord
{
    protected static string $resource = MemberStatisticResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
