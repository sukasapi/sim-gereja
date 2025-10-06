<?php

namespace App\Filament\Admin\Resources\ProposalResource\Widgets;

use App\Models\Proposal;
use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ProposalsChart extends ChartWidget implements HasForms
{
    use InteractsWithForms;

    protected static ?string $heading = 'Ringkasan Proposal';

    protected static string $color = 'info';

    protected static ?int $sort = -2; // Tampilkan di atas tabel

    // Properti publik yang akan diikat ke form untuk filtering
    public ?string $startDate = null;
    public ?string $endDate = null;

    public function mount(): void
    {
        // Mengisi form dengan nilai default saat mount, ini akan memperbarui properti publik
        $this->form->fill([
            'startDate' => Carbon::now()->startOfYear(),
            'endDate' => Carbon::now()
        ]);
        // Tidak perlu memanggil updateChartData() atau updateChart() di sini
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('startDate')
                    ->label('Dari Tanggal')
                    ->live() // Menggunakan live untuk reactivity
                    ->afterStateUpdated(fn (string $state) => $this->startDate = $state), // Update properti publik secara manual jika live() saja belum cukup
                DatePicker::make('endDate')
                    ->label('Sampai Tanggal')
                    ->live() // Menggunakan live untuk reactivity
                    ->afterStateUpdated(fn (string $state) => $this->endDate = $state), // Update properti publik secara manual jika live() saja belum cukup
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

        $query = Proposal::query();

        if ($startDate && $endDate) {
            $query->whereBetween('request_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('request_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('request_date', '<=', $endDate);
        }

        $proposals = $query->get();

        $statusCounts = $proposals->groupBy('status')->map->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Proposal',
                    'data' => $statusCounts->values()->toArray(),
                    'backgroundColor' => ['#ffc107', '#28a745', '#dc3545'], // Warna untuk pending, approved, rejected
                    'borderColor' => ['#ffc107', '#28a745', '#dc3545'],
                ],
            ],
            'labels' => $statusCounts->keys()->map(fn ($key) => match ($key) {
                'pending' => 'Menunggu',
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
                default => $key,
            })->toArray(),
        ];
    }

    // Method updateChartData() dihapus

    // Tambahkan method ini jika diperlukan untuk filter lain atau data pemohon
    // protected function getFilters(): ?array
    // {
    //     return [
    //         //
    //     ];
    // }
}
