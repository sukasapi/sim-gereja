<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MemberImportResource\Pages;
use App\Filament\Admin\Resources\MemberImportResource\RelationManagers;
use App\Models\MemberImport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemberImportResource extends Resource
{
    protected static ?string $model = MemberImport::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';
    
    protected static ?string $navigationGroup = 'Data Jemaat';
    
    protected static ?string $navigationLabel = 'Import Jemaat';
    
    protected static ?string $pluralModelLabel = 'Import Jemaat';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Template dan Panduan Import')
                    ->schema([
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('download_template')
                                ->label('Download Template CSV')
                                ->icon('heroicon-o-arrow-down-tray')
                                ->color('info')
                                ->action(function () {
                                    $templatePath = storage_path('app/templates/template_import_jemaat.csv');
                                    if (file_exists($templatePath)) {
                                        return response()->download($templatePath, 'template_import_jemaat.csv');
                                    }
                                    throw new \Exception('Template file tidak ditemukan');
                                }),
                        ]),
                        Forms\Components\Placeholder::make('template_info')
                            ->content('Template CSV berisi contoh data dengan format yang benar. Silakan download template dan isi dengan data jemaat yang akan diimpor.'),
                    ])->collapsible(),
                
                Forms\Components\Section::make('Daftar Wilayah Tersedia')
                    ->schema([
                        Forms\Components\Placeholder::make('regions_list')
                            ->content(function () {
                                $user = auth()->user();
                                $churchId = $user->church_id;
                                
                                if ($user->isSuperAdmin()) {
                                    $regions = \App\Models\Region::with('church', 'regionType')
                                        ->orderBy('church_id')
                                        ->orderBy('name')
                                        ->get();
                                } else {
                                    $regions = \App\Models\Region::with('regionType')
                                        ->where('church_id', $churchId)
                                        ->orderBy('name')
                                        ->get();
                                }
                                
                                if ($regions->isEmpty()) {
                                    return 'Belum ada wilayah yang tersedia. Silakan tambahkan wilayah terlebih dahulu.';
                                }
                                
                                $html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
                                foreach ($regions as $region) {
                                    $churchName = $user->isSuperAdmin() ? " ({$region->church->name})" : '';
                                    $html .= "<div class='p-2 bg-gray-100 rounded text-sm'>";
                                    $html .= "<strong>{$region->name}</strong>{$churchName}<br>";
                                    $html .= "<span class='text-gray-600'>Tipe: {$region->regionType->name}</span>";
                                    $html .= "</div>";
                                }
                                $html .= '</div>';
                                
                                return new \Illuminate\Support\HtmlString($html);
                            }),
                    ])->collapsible(),
                
                Forms\Components\Section::make('Upload File CSV')
                    ->schema([
                        Forms\Components\Select::make('church_id')
                            ->label('Gereja')
                            ->relationship('church', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->default(fn () => auth()->user()->church_id)
                            ->disabled(fn () => !auth()->user()->isSuperAdmin())
                            ->helperText('Superadmin dapat memilih gereja manapun, admin gereja hanya dapat memilih gerejanya sendiri'),
                        Forms\Components\FileUpload::make('csv_file')
                            ->label('File CSV')
                            ->acceptedFileTypes(['text/csv', 'application/csv'])
                            ->required()
                            ->helperText('Format CSV: nama, tanggal_lahir, jenis_kelamin, alamat, telepon, email, wilayah, ayah, ibu, tanggal_gabung, status_baptis, status_sidi, catatan_pelayanan, catatan_umum')
                            ->disk('local')
                            ->directory('imports')
                            ->visibility('private'),
                        Forms\Components\Toggle::make('skip_duplicates')
                            ->label('Skip Duplikat')
                            ->helperText('Lewati baris yang sudah ada berdasarkan nama dan tanggal lahir')
                            ->default(true),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('filename')
                    ->label('Nama File')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('church.name')
                    ->label('Gereja')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_rows')
                    ->label('Total Baris')
                    ->numeric()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('imported_rows')
                    ->label('Berhasil')
                    ->numeric()
                    ->alignCenter()
                    ->color('success'),
                Tables\Columns\TextColumn::make('skipped_rows')
                    ->label('Dilewati')
                    ->numeric()
                    ->alignCenter()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('error_rows')
                    ->label('Error')
                    ->numeric()
                    ->alignCenter()
                    ->color('danger'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'processing' => 'warning',
                        'completed' => 'success',
                        'failed' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('importedBy.name')
                    ->label('Diimpor Oleh')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('started_at')
                    ->label('Mulai')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Selesai')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('church_id')
                    ->label('Gereja')
                    ->relationship('church', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('download_template')
                    ->label('Download Template CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->action(function () {
                        $templatePath = storage_path('app/templates/template_import_jemaat.csv');
                        if (file_exists($templatePath)) {
                            return response()->download($templatePath, 'template_import_jemaat.csv');
                        }
                        throw new \Exception('Template file tidak ditemukan');
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMemberImports::route('/'),
            'create' => Pages\CreateMemberImport::route('/create'),
            'edit' => Pages\EditMemberImport::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();
        if ($user && !$user->isSuperAdmin() && $user->church_id) {
            $query->where('church_id', $user->church_id);
        }

        return $query;
    }
}
