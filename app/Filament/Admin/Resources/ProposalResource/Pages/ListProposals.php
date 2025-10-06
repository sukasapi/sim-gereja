<?php

namespace App\Filament\Admin\Resources\ProposalResource\Pages;

use App\Filament\Admin\Resources\ProposalResource;
use App\Filament\Admin\Resources\ProposalResource\Widgets\ProposalsChart;
use App\Filament\Admin\Resources\ProposalResource\Widgets\ProposalsByRecipientCountChart;
use App\Filament\Admin\Resources\ProposalResource\Widgets\ProposalStatsOverview;
use App\Filament\Admin\Resources\ProposalResource\Widgets\RecipientProposalsStatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProposals extends ListRecords
{
    protected static string $resource = ProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ProposalStatsOverview::class,
            RecipientProposalsStatsOverview::class,
            ProposalsChart::class,
            ProposalsByRecipientCountChart::class,
        ];
    }
}
