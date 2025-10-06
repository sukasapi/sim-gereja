<?php

namespace App\Filament\Admin\Resources\ProposalResource\Widgets;

use App\Models\ProposalRecipient;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RecipientProposalsStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $totalRecipientEntries = ProposalRecipient::count();
        $uniqueRecipients = ProposalRecipient::distinct('recipient_name')->count();

        return [
            Stat::make('Total Entri Penerima', $totalRecipientEntries)
                ->description('Jumlah total baris di tabel penerima')
                ->color('info'),
            Stat::make('Penerima Unik', $uniqueRecipients)
                ->description('Jumlah pemohon unik yang terdaftar')
                ->color('success'),
            // Anda bisa menambahkan stat lain di sini jika diperlukan, 
            // misalnya jumlah berdasarkan status penerima jika relasi dan data tersedia.
        ];
    }
}
