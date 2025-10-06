<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProjectWorkItemResource\Pages;
use App\Models\ProjectWorkItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Forms\Components\MoneyInput;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProjectWorkItemResource extends Resource
{
    protected static ?string $model = ProjectWorkItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Uraian Pekerjaan';

    protected static ?string $modelLabel = 'Uraian Pekerjaan';

    protected static ?string $pluralModelLabel = 'Uraian Pekerjaan';

    protected static ?string $navigationGroup = 'Proyek Gereja';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project_id')
                    ->label('Proyek')
                    ->relationship('project', 'name')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        $set('sub_project_id', null);
                    }),
                Forms\Components\Select::make('sub_project_id')
                    ->label('Sub Proyek')
                    ->relationship('subProject', 'name', function ($query, Forms\Get $get) {
                        return $query->where('project_id', $get('project_id'));
                    })
                    ->nullable()
                    ->visible(fn (Forms\Get $get) => filled($get('project_id'))),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Uraian Pekerjaan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                Forms\Components\Select::make('input_type')
                    ->label('Tipe Input')
                    ->options([
                        'budget' => 'Anggaran',
                        'realization' => 'Realisasi',
                    ])
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        if ($state === 'budget') {
                            $set('realization_amount', 0);
                            // Hitung ulang total anggaran dari sub pekerjaan
                            $total = collect($get('subItems'))
                                ->sum(function ($item) {
                                    return (int) Str::of($item['budget_amount'] ?? 0)->replace(['.', ','], '')->toString();
                                });
                            $set('budget_amount', $total);
                        } else {
                            $set('budget_amount', 0);
                            // Hitung ulang total realisasi dari sub pekerjaan
                            $total = collect($get('subItems'))
                                ->sum(function ($item) {
                                    return (int) Str::of($item['realization_amount'] ?? 0)->replace(['.', ','], '')->toString();
                                });
                            $set('realization_amount', $total);
                        }
                    }),
                Forms\Components\Section::make('Sub Pekerjaan')
                    ->description('Opsional - Jika tidak ada sub pekerjaan, Anda dapat langsung mengisi anggaran/realisasi di bawah')
                    ->schema([
                        Forms\Components\Repeater::make('subItems')
                            ->label('Daftar Sub Pekerjaan')
                            ->relationship('subItems', function ($query) {
                                $recordId = request()->route('record');
                                if (!$recordId) {
                                    return $query->whereRaw('1 = 0');
                                }
                                return $query->where('project_work_item_id', $recordId);
                            })
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Sub Pekerjaan')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi')
                                    ->columnSpanFull(),
                                MoneyInput::make('budget_amount')
                                    ->label('Anggaran')
                                    ->required()
                                    ->prefix('Rp')
                                    ->visible(fn (Forms\Get $get) => $get('../../input_type') === 'budget')
                                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                        if ($get('../../input_type') === 'budget') {
                                            $total = collect($get('../../subItems'))
                                                ->sum(function ($item) {
                                                    return (int) Str::of($item['budget_amount'] ?? 0)->replace(['.', ','], '')->toString();
                                                });
                                            $set('../../budget_amount', $total);
                                        }
                                    }),
                                MoneyInput::make('realization_amount')
                                    ->label('Realisasi')
                                    ->required()
                                    ->prefix('Rp')
                                    ->visible(fn (Forms\Get $get) => $get('../../input_type') === 'realization')
                                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                        if ($get('../../input_type') === 'realization') {
                                            $total = collect($get('../../subItems'))
                                                ->sum(function ($item) {
                                                    return (int) Str::of($item['realization_amount'] ?? 0)->replace(['.', ','], '')->toString();
                                                });
                                            $set('../../realization_amount', $total);
                                        }
                                    }),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->reorderable(false)
                            ->columnSpanFull()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                if ($get('input_type') === 'budget') {
                                    $total = collect($state)->sum(function ($item) {
                                        return (int) Str::of($item['budget_amount'] ?? 0)->replace(['.', ','], '')->toString();
                                    });
                                    $set('budget_amount', $total);
                                } else {
                                    $total = collect($state)->sum(function ($item) {
                                        return (int) Str::of($item['realization_amount'] ?? 0)->replace(['.', ','], '')->toString();
                                    });
                                    $set('realization_amount', $total);
                                }
                            }),
                    ])
                    ->collapsible()
                    ->collapsed(fn (Forms\Get $get) => !$get('input_type')),
                MoneyInput::make('budget_amount')
                    ->label('Total Anggaran')
                    ->required()
                    ->prefix('Rp')
                    ->disabled(fn (Forms\Get $get) => $get('input_type') === 'realization' || count($get('subItems') ?? []) > 0)
                    ->helperText(fn (Forms\Get $get) => count($get('subItems') ?? []) > 0 ? 'Total akan dihitung otomatis dari sub pekerjaan' : 'Isi langsung jika tidak ada sub pekerjaan')
                    ->afterStateHydrated(function (MoneyInput $component, $state, Forms\Get $get) {
                        if (count($get('subItems') ?? []) > 0 && $get('input_type') === 'budget') {
                            $total = collect($get('subItems'))
                                ->sum(function ($item) {
                                    return (int) Str::of($item['budget_amount'] ?? 0)->replace(['.', ','], '')->toString();
                                });
                            $component->state($total);
                        }
                    })
                    ->dehydrateStateUsing(function ($state, Forms\Get $get) {
                        if (count($get('subItems') ?? []) > 0 && $get('input_type') === 'budget') {
                            return collect($get('subItems'))
                                ->sum(function ($item) {
                                    return (int) Str::of($item['budget_amount'] ?? 0)->replace(['.', ','], '')->toString();
                                });
                        }
                        return (int) Str::of($state)->replace(['.', ','], '')->toString();
                    }),
                MoneyInput::make('realization_amount')
                    ->label('Total Realisasi')
                    ->required()
                    ->prefix('Rp')
                    ->disabled(fn (Forms\Get $get) => $get('input_type') === 'budget' || count($get('subItems') ?? []) > 0)
                    ->helperText(fn (Forms\Get $get) => count($get('subItems') ?? []) > 0 ? 'Total akan dihitung otomatis dari sub pekerjaan' : 'Isi langsung jika tidak ada sub pekerjaan')
                    ->afterStateHydrated(function (MoneyInput $component, $state, Forms\Get $get) {
                        if (count($get('subItems') ?? []) > 0 && $get('input_type') === 'realization') {
                            $total = collect($get('subItems'))
                                ->sum(function ($item) {
                                    return (int) Str::of($item['realization_amount'] ?? 0)->replace(['.', ','], '')->toString();
                                });
                            $component->state($total);
                        }
                    })
                    ->dehydrateStateUsing(function ($state, Forms\Get $get) {
                        if (count($get('subItems') ?? []) > 0 && $get('input_type') === 'realization') {
                            return collect($get('subItems'))
                                ->sum(function ($item) {
                                    return (int) Str::of($item['realization_amount'] ?? 0)->replace(['.', ','], '')->toString();
                                });
                        }
                        return (int) Str::of($state)->replace(['.', ','], '')->toString();
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Proyek')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subProject.name')
                    ->label('Sub Proyek')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Uraian Pekerjaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('budget_amount')
                    ->label('Anggaran')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('realization_amount')
                    ->label('Realisasi')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record) {
                        // Hapus semua sub pekerjaan terlebih dahulu
                        $record->subItems()->delete();
                    })
                    ->after(function () {
                        // Reset auto increment
                        $maxId = \App\Models\ProjectWorkItem::max('id') ?? 0;
                        \Illuminate\Support\Facades\DB::statement("ALTER TABLE project_work_items AUTO_INCREMENT = " . ($maxId + 1));
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            // Hapus semua sub pekerjaan terlebih dahulu
                            foreach ($records as $record) {
                                $record->subItems()->delete();
                            }
                        })
                        ->after(function () {
                            // Reset auto increment
                            $maxId = \App\Models\ProjectWorkItem::max('id') ?? 0;
                            \Illuminate\Support\Facades\DB::statement("ALTER TABLE project_work_items AUTO_INCREMENT = " . ($maxId + 1));
                        }),
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
            'index' => Pages\ListProjectWorkItems::route('/'),
            'create' => Pages\CreateProjectWorkItem::route('/create'),
            'edit' => Pages\EditProjectWorkItem::route('/{record}/edit'),
        ];
    }
} 