<?php

namespace App\Filament\Admin\Resources\ProposalResource\Widgets;

use App\Models\Proposal;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProposalStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $totalProposals = Proposal::count();
        $pendingProposals = Proposal::where('status', 'pending')->count();
        $approvedProposals = Proposal::where('status', 'approved')->count();
        $rejectedProposals = Proposal::where('status', 'rejected')->count();

        return [
            Stat::make('Total Proposal', $totalProposals)
                ->description('Jumlah seluruh permintaan proposal')
                ->color('primary'),
            Stat::make('Menunggu', $pendingProposals)
                ->description('Proposal dalam status menunggu')
                ->color('warning'),
            Stat::make('Disetujui', $approvedProposals)
                ->description('Proposal yang sudah disetujui')
                ->color('success'),
            Stat::make('Ditolak', $rejectedProposals)
                ->description('Proposal yang ditolak')
                ->color('danger'),
        ];
    }
}
