<?php

namespace App\Filament\Admin\Resources\ProposalResource\Widgets;

use App\Models\Proposal;
use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProposalsByRecipientCountChart extends ChartWidget implements HasForms
{
    use InteractsWithForms;

    protected static ?string $heading = 'Proposal Berdasarkan Jumlah Penerima';

    protected static string $color = 'success';

    protected static ?int $sort = -1; // Tampilkan di atas chart sebelumnya

    // Properti publik untuk filtering
    public ?string $startDate = null;
    public ?string $endDate = null;

    public function mount(): void
    {
        // Mengisi form dengan nilai default saat mount
        $this->form->fill([
            'startDate' => Carbon::now()->startOfYear(),
            'endDate' => Carbon::now()
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('startDate')
                    ->label('Dari Tanggal')
                    ->live() // Menggunakan live untuk reactivity
                    ->afterStateUpdated(fn (string $state) => $this->startDate = $state),
                DatePicker::make('endDate')
                    ->label('Sampai Tanggal')
                    ->live() // Menggunakan live untuk reactivity
                    ->afterStateUpdated(fn (string $state) => $this->endDate = $state),
            ])
            ->columns(2);
    }

    protected function getType(): string
    {
        return 'bar'; // Menggunakan Bar Chart
    }

    protected function getData(): array
    {
        // Mengambil data berdasarkan properti publik yang diperbarui oleh form
        $startDate = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : null;
        $endDate = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : null;

        $query = Proposal::query()
            ->withCount('recipients'); // Menghitung jumlah penerima

        if ($startDate && $endDate) {
            $query->whereBetween('request_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('request_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('request_date', '<=', $endDate);
        }

        $proposals = $query->get();

        // Mengelompokkan proposal berdasarkan jumlah penerima
        $recipientCountGroups = $proposals->groupBy(function ($proposal) {
            $count = $proposal->recipients_count;
            if ($count === 0) return '0 Penerima';
            if ($count === 1) return '1 Penerima';
            if ($count === 2) return '2 Penerima';
            return '>2 Penerima'; // Kelompokkan 3 atau lebih
        })->map->count();

        // Urutkan label agar 0, 1, 2, >2
        $labelsOrder = ['0 Penerima', '1 Penerima', '2 Penerima', '>2 Penerima'];
        $orderedCounts = collect($labelsOrder)->mapWithKeys(function ($label) use ($recipientCountGroups) {
            return [$label => $recipientCountGroups->get($label, 0)];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Proposal',
                    'data' => $orderedCounts->values()->toArray(),
                    'backgroundColor' => '#28a745',
                    'borderColor' => '#28a745',
                ],
            ],
            'labels' => $orderedCounts->keys()->toArray(),
        ];
    }
}
