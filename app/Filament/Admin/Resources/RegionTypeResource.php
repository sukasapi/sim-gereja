<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RegionTypeResource\Pages;
use App\Filament\Admin\Resources\RegionTypeResource\RelationManagers;
use App\Models\RegionType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RegionTypeResource extends Resource
{
    protected static ?string $model = RegionType::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    
    protected static ?string $navigationGroup = 'Wilayah';
    
    protected static ?string $navigationLabel = 'Tipe Wilayah';
    protected static ?string $modelLabel = 'Tipe Wilayah';
    protected static ?string $pluralModelLabel = 'Tipe Wilayah';
    protected static ?int $navigationSort = 2;

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
                    ->disabled(fn () => !auth()->user()->isSuperAdmin()),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Tipe')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Contoh: Blok, Pepanthan, Sektor'),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->placeholder('Contoh: blok, pepanthan, sektor'),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3),
                Forms\Components\Select::make('parent_id')
                    ->label('Tipe Wilayah Induk')
                    ->relationship('parent', 'name', modifyQueryUsing: function ($query, $get) {
                        return $query->where('church_id', $get('church_id'));
                    })
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            // Auto-set level berdasarkan parent
                            $parent = \App\Models\RegionType::find($state);
                            if ($parent) {
                                $set('level', $parent->level + 1);
                            }
                        } else {
                            $set('level', 1);
                        }
                    }),
                Forms\Components\TextInput::make('level')
                    ->label('Level')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->maxValue(10)
                    ->disabled(fn ($get) => $get('parent_id')), // Disable jika ada parent
                Forms\Components\TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Tipe')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Tipe Induk')
                    ->sortable()
                    ->placeholder('Root'),
                Tables\Columns\TextColumn::make('level')
                    ->label('Level')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('full_path')
                    ->label('Path Lengkap')
                    ->getStateUsing(fn ($record) => $record->full_path),
                Tables\Columns\TextColumn::make('regions_count')
                    ->label('Jumlah Wilayah')
                    ->counts('regions')
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
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Tipe Induk')
                    ->relationship('parent', 'name')
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
            ->defaultSort('sort_order');
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
        
        // Admin gereja dan admin komisi hanya bisa lihat tipe wilayah di gereja mereka
        return parent::getEloquentQuery()->where('church_id', $user->church_id);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegionTypes::route('/'),
            'create' => Pages\CreateRegionType::route('/create'),
            'edit' => Pages\EditRegionType::route('/{record}/edit'),
        ];
    }
}
