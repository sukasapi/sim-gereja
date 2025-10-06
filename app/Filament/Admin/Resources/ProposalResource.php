<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProposalResource\Pages;
use App\Filament\Admin\Resources\ProposalResource\RelationManagers;
use App\Models\Proposal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;

class ProposalResource extends Resource
{
    protected static ?string $model = Proposal::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Request Proposal';

    protected static ?string $modelLabel = 'Request Proposal';

    protected static ?string $pluralModelLabel = 'Request Proposal';

    protected static ?string $navigationGroup = 'Manajemen Donasi';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project_id')
                    ->label('Proyek')
                    ->relationship('project', 'name')
                    ->required(),
                Forms\Components\TextInput::make('requester')
                    ->label('Pemohon')
                    ->required()
                    ->maxLength(255)
                    ->live(),
                Forms\Components\Select::make('status')
                    ->label('Status Proposal Utama')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\DatePicker::make('request_date')
                    ->label('Tanggal Permintaan')
                    ->default(Carbon::now())
                    ->required(),
                Forms\Components\DatePicker::make('sent_date')
                    ->label('Tanggal Pengiriman'),
                Forms\Components\FileUpload::make('file')
                    ->label('File Proposal')
                    ->acceptedFileTypes(['application/pdf'])
                    ->directory('proposals')
                    ->columnSpanFull(),
                Forms\Components\Section::make('Penerima Proposal')
                    ->schema([
                        Forms\Components\Placeholder::make('recipients_info')
                            ->label('Daftar Penerima')
                            ->content(function (Forms\Get $get) {
                                if (!$get('requester')) {
                                    return 'Silakan isi nama pemohon terlebih dahulu';
                                }
                                return null;
                            }),
                        Forms\Components\HasManyRepeater::make('recipients')
                            ->label('')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('recipient_name')
                                    ->label('Nama Penerima')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('recipient_address')
                                    ->label('Alamat Penerima')
                                    ->required()
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->required()
                                    ->numeric()
                                    ->default(1),
                                Forms\Components\Select::make('status')
                                    ->label('Status Penerima')
                                    ->options([
                                        'pending' => 'Menunggu',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                    ])
                                    ->default('pending')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->minItems(1)
                            ->reorderable(false)
                            ->createItemButtonLabel('Tambah Penerima')
                            ->columnSpanFull()
                            ->visible(fn (Forms\Get $get) => filled($get('requester')))
                            ->itemLabel(function (array $state, Forms\Get $get): ?string {
                                $proposalStatus = $state['status'] ?? ($get('../status') ?? 'pending');
                                $requestDate = $get('../request_date');
                                $label = $state['recipient_name'] ?? null;

                                if ($label) {
                                    if ($proposalStatus) {
                                        $label .= ' (' . Str::ucfirst($proposalStatus) . ')';
                                    }
                                    if ($requestDate) {
                                        try {
                                            $date = Carbon::parse($requestDate)->format('d/m/Y');
                                            $label .= ' - ' . $date;
                                        } catch (\Exception $e) {
                                            // Do nothing if date parsing fails
                                        }
                                    }
                                }

                                return $label;
                            })
                            ->collapsible()
                            ->collapsed(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project.name')
                    ->label('Proyek')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('requester')
                    ->label('Pemohon')
                    ->searchable()
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
                TextColumn::make('request_date')
                    ->label('Tanggal Permintaan')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('sent_date')
                    ->label('Tanggal Pengiriman')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('recipients_count')
                    ->counts('recipients')
                    ->label('Jumlah Penerima'),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('viewRecipients')
                    ->label('Lihat Penerima')
                    ->icon('heroicon-o-users')
                    ->modalHeading(fn (Proposal $record): string => "Penerima - {$record->requester}")
                    ->modalContent(fn (Proposal $record) => View::make('filament.resources.proposal-resource.pages.recipients-modal', [
                        'proposal' => $record,
                        'recipients' => $record->recipients,
                    ])),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\RecipientsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProposals::route('/'),
            'create' => Pages\CreateProposal::route('/create'),
            'edit' => Pages\EditProposal::route('/{record}/edit'),
        ];
    }
}
