<?php

namespace App\Filament\Admin\Resources\ProposalResource\Widgets;

use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use App\Models\Proposal;
use App\Models\ProposalRecipient;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class RecipientsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    public ?string $selectedProposalId = null;

    public function mount(?string $selectedProposalId = null): void
    {
        $this->selectedProposalId = $selectedProposalId;
    }

    protected function getTableHeading(): ?string
    {
        if ($this->selectedProposalId) {
            $proposal = Proposal::find($this->selectedProposalId);
            return 'Penerima Proposal: ' . ($proposal ? $proposal->requester : 'N/A');
        }

        return 'Penerima Proposal';
    }

    protected function getTableQuery(): Builder|Relation|null
    {
        if ($this->selectedProposalId) {
            return ProposalRecipient::query()->where('proposal_id', $this->selectedProposalId);
        }

        return ProposalRecipient::query()->where('proposal_id', null);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('recipient_name')
                ->label('Nama Penerima')
                ->searchable()
                ->sortable(),
            TextColumn::make('recipient_address')
                ->label('Alamat Penerima')
                ->searchable()
                ->sortable(),
            TextColumn::make('quantity')
                ->label('Jumlah')
                ->numeric()
                ->sortable(),
            TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'pending' => 'warning',
                    'approved' => 'success',
                    'rejected' => 'danger',
                })
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'pending' => 'Menunggu',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                }),
        ];
    }
}
