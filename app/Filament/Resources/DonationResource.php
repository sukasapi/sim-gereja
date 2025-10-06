<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Models\Donation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationLabel = 'Donasi';

    protected static ?string $modelLabel = 'Donasi';

    protected static ?string $pluralModelLabel = 'Donasi';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Manajemen';

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->role !== 'jemaat';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('donor_name')
                    ->label('Nama Donatur')
                    ->required(),
                Forms\Components\Textarea::make('donor_address')
                    ->label('Alamat/Asal')
                    ->required(),
                Forms\Components\Select::make('donation_type')
                    ->label('Jenis Donasi')
                    ->options([
                        'internal' => 'Internal',
                        'external' => 'Eksternal',
                    ])
                    ->required(),
                Forms\Components\Select::make('donation_category')
                    ->label('Kategori Donasi')
                    ->options([
                        'barang' => 'Barang',
                        'dana' => 'Dana',
                    ])
                    ->required(),
                Forms\Components\Select::make('donation_size')
                    ->label('Ukuran Donasi')
                    ->options([
                        'besar' => 'Besar',
                        'kecil' => 'Kecil',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Jumlah (Rp)')
                    ->numeric()
                    ->nullable(),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->nullable(),
                Forms\Components\Select::make('proposal_id')
                    ->label('Proposal')
                    ->relationship('proposal', 'title')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('donor_name')
                    ->label('Nama Donatur')
                    ->searchable(),
                Tables\Columns\TextColumn::make('donation_type')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'internal' => 'success',
                        'external' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('donation_category')
                    ->label('Kategori')
                    ->badge(),
                Tables\Columns\TextColumn::make('donation_size')
                    ->label('Ukuran')
                    ->badge(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('donation_type')
                    ->options([
                        'internal' => 'Internal',
                        'external' => 'Eksternal',
                    ]),
                Tables\Filters\SelectFilter::make('donation_category')
                    ->options([
                        'barang' => 'Barang',
                        'dana' => 'Dana',
                    ]),
                Tables\Filters\SelectFilter::make('donation_size')
                    ->options([
                        'besar' => 'Besar',
                        'kecil' => 'Kecil',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListDonations::route('/'),
            'create' => Pages\CreateDonation::route('/create'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('created_by', Auth::id());
    }
} 