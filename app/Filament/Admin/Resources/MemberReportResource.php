<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MemberReportResource\Pages;
use App\Filament\Admin\Resources\MemberReportResource\RelationManagers;
use App\Models\MemberReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemberReportResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    
    protected static ?string $navigationGroup = 'Data Jemaat';
    
    protected static ?string $navigationLabel = 'Laporan Jemaat';
    protected static ?string $modelLabel = 'Laporan Jemaat';
    protected static ?string $pluralModelLabel = 'Laporan Jemaat';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('church_id')
                    ->label('Gereja')
                    ->relationship('church', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->default(fn () => current_church_id())
                    ->disabled(fn () => !auth()->user()->isSuperAdmin()),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Jemaat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('birth_date')
                    ->label('Tanggal Lahir'),
                Forms\Components\Select::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),
                Forms\Components\Textarea::make('address')
                    ->label('Alamat')
                    ->rows(3),
                Forms\Components\TextInput::make('phone')
                    ->label('Telepon')
                    ->tel(),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('church.name')
                    ->label('Gereja')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('birth_date')
                    ->label('Tanggal Lahir')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('church_id')
                    ->label('Gereja')
                    ->relationship('church', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->boolean()
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMemberReports::route('/'),
            'create' => Pages\CreateMemberReport::route('/create'),
            'edit' => Pages\EditMemberReport::route('/{record}/edit'),
        ];
    }
}
