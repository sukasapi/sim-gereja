<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RegionResource\Pages;
use App\Filament\Admin\Resources\RegionResource\RelationManagers;
use App\Models\Region;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RegionResource extends Resource
{
    protected static ?string $model = Region::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';
    
    protected static ?string $navigationGroup = 'Wilayah';
    
    protected static ?string $navigationLabel = 'Wilayah';
    protected static ?string $modelLabel = 'Wilayah';
    protected static ?string $pluralModelLabel = 'Wilayah';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('church_id')
                    ->label('Gereja')
                    ->relationship('church', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->default(fn () => auth()->user()->church_id)
                    ->disabled(fn () => !auth()->user()->isSuperAdmin())
                    ->reactive(),
                Forms\Components\Select::make('region_type_id')
                    ->label('Tipe Wilayah')
                    ->relationship('regionType', 'name', modifyQueryUsing: function ($query, $get) {
                        return $query->where('church_id', $get('church_id'));
                    })
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Wilayah')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3),
                Forms\Components\Select::make('parent_id')
                    ->label('Wilayah Induk')
                    ->relationship('parent', 'name', modifyQueryUsing: function ($query, $get) {
                        return $query->where('church_id', $get('church_id'));
                    })
                    ->searchable()
                    ->preload(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('church.name')
                    ->label('Gereja')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('regionType.name')
                    ->label('Tipe')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Wilayah')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Wilayah Induk')
                    ->sortable(),
                Tables\Columns\TextColumn::make('members_count')
                    ->label('Jumlah Jemaat')
                    ->counts('members')
                    ->alignCenter(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('church_id')
                    ->label('Gereja')
                    ->relationship('church', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('region_type_id')
                    ->label('Tipe Wilayah')
                    ->relationship('regionType', 'name')
                    ->searchable()
                    ->preload(),
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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            return parent::getEloquentQuery();
        }
        
        // Admin gereja dan admin komisi hanya bisa lihat wilayah di gereja mereka
        return parent::getEloquentQuery()->where('church_id', $user->church_id);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegions::route('/'),
            'create' => Pages\CreateRegion::route('/create'),
            'edit' => Pages\EditRegion::route('/{record}/edit'),
        ];
    }
}
