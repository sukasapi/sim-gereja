<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MemberResource\Pages;
use App\Filament\Admin\Resources\MemberResource\RelationManagers;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationGroup = 'Data Jemaat';
    
    protected static ?string $navigationLabel = 'Data Jemaat';
    protected static ?string $modelLabel = 'Jemaat';
    protected static ?string $pluralModelLabel = 'Data Jemaat';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pribadi')
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
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('birth_date')
                            ->label('Tanggal Lahir')
                            ->displayFormat('d/m/Y'),
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
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('photo')
                            ->label('Foto')
                            ->image()
                            ->directory('member-photos'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Wilayah')
                    ->schema([
                        Forms\Components\Select::make('region_id')
                            ->label('Wilayah')
                            ->relationship('region', 'name', modifyQueryUsing: function ($query, $get) {
                                return $query->where('church_id', $get('church_id'));
                            })
                            ->searchable()
                            ->preload()
                            ->reactive(),
                    ]),
                
                Forms\Components\Section::make('Silsilah Keluarga')
                    ->schema([
                        Forms\Components\Select::make('father_id')
                            ->label('Ayah')
                            ->relationship('father', 'name', modifyQueryUsing: function ($query, $get) {
                                return $query->where('church_id', $get('church_id'))
                                    ->where('gender', 'L');
                            })
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('mother_id')
                            ->label('Ibu')
                            ->relationship('mother', 'name', modifyQueryUsing: function ($query, $get) {
                                return $query->where('church_id', $get('church_id'))
                                    ->where('gender', 'P');
                            })
                            ->searchable()
                            ->preload(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Informasi Gereja')
                    ->schema([
                        Forms\Components\DatePicker::make('join_date')
                            ->label('Tanggal Gabung Gereja')
                            ->displayFormat('d/m/Y'),
                        Forms\Components\Toggle::make('is_baptized')
                            ->label('Sudah Dibaptis')
                            ->reactive(),
                        Forms\Components\DatePicker::make('baptism_date')
                            ->label('Tanggal Baptis')
                            ->displayFormat('d/m/Y')
                            ->visible(fn ($get) => $get('is_baptized')),
                        Forms\Components\Toggle::make('is_sidi')
                            ->label('Sudah Sidi')
                            ->reactive(),
                        Forms\Components\DatePicker::make('sidi_date')
                            ->label('Tanggal Sidi')
                            ->displayFormat('d/m/Y')
                            ->visible(fn ($get) => $get('is_sidi')),
                    ])->columns(2),
                
                Forms\Components\Section::make('Catatan')
                    ->schema([
                        Forms\Components\Textarea::make('ministry_notes')
                            ->label('Catatan Pelayanan')
                            ->rows(3),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Umum')
                            ->rows(3),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular()
                    ->size(40),
                Tables\Columns\TextColumn::make('church.name')
                    ->label('Gereja')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender')
                    ->label('JK')
                    ->formatStateUsing(fn (string $state): string => $state === 'L' ? 'L' : 'P')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'L' ? 'info' : 'warning'),
                Tables\Columns\TextColumn::make('age')
                    ->label('Umur')
                    ->getStateUsing(fn ($record) => $record->age ? $record->age . ' tahun' : '-')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('region.name')
                    ->label('Wilayah')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('father.name')
                    ->label('Ayah')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('mother.name')
                    ->label('Ibu')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Baptis, Sidi' => 'success',
                        'Baptis' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
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
                Tables\Filters\SelectFilter::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),
                Tables\Filters\TernaryFilter::make('is_baptized')
                    ->label('Status Baptis')
                    ->boolean()
                    ->trueLabel('Sudah Dibaptis')
                    ->falseLabel('Belum Dibaptis')
                    ->native(false),
                Tables\Filters\TernaryFilter::make('is_sidi')
                    ->label('Status Sidi')
                    ->boolean()
                    ->trueLabel('Sudah Sidi')
                    ->falseLabel('Belum Sidi')
                    ->native(false),
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
            ->headerActions([
                Tables\Actions\Action::make('export_csv')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->action(function () {
                        $user = auth()->user();
                        $query = \App\Models\Member::query();
                        
                        // Filter berdasarkan gereja untuk non-superadmin
                        if (!$user->isSuperAdmin() && $user->church_id) {
                            $query->where('church_id', $user->church_id);
                        }
                        
                        $members = $query->with(['church', 'region', 'father', 'mother'])->get();
                        
                        $filename = 'export_jemaat_' . now()->format('Y-m-d_H-i-s') . '.csv';
                        $filepath = storage_path('app/exports/' . $filename);
                        
                        // Ensure directory exists
                        if (!file_exists(storage_path('app/exports'))) {
                            mkdir(storage_path('app/exports'), 0755, true);
                        }
                        
                        $file = fopen($filepath, 'w');
                        
                        // Write header
                        fputcsv($file, [
                            'nama',
                            'tanggal_lahir',
                            'jenis_kelamin',
                            'alamat',
                            'telepon',
                            'email',
                            'gereja',
                            'wilayah',
                            'ayah',
                            'ibu',
                            'tanggal_gabung',
                            'status_baptis',
                            'status_sidi',
                            'catatan_pelayanan',
                            'catatan_umum',
                            'status_aktif'
                        ]);
                        
                        // Write data
                        foreach ($members as $member) {
                            fputcsv($file, [
                                $member->name,
                                $member->birth_date ? $member->birth_date->format('d/m/Y') : '',
                                $member->gender === 'L' ? 'L' : 'P',
                                $member->address,
                                $member->phone,
                                $member->email,
                                $member->church->name,
                                $member->region?->name,
                                $member->father?->name,
                                $member->mother?->name,
                                $member->join_date ? $member->join_date->format('d/m/Y') : '',
                                $member->is_baptized ? 'Ya' : 'Tidak',
                                $member->is_sidi ? 'Ya' : 'Tidak',
                                $member->ministry_notes,
                                $member->notes,
                                $member->is_active ? 'Aktif' : 'Tidak Aktif'
                            ]);
                        }
                        
                        fclose($file);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Export CSV Berhasil')
                            ->body("File berhasil diekspor: {$filename}")
                            ->success()
                            ->send();
                        
                        return response()->download($filepath)->deleteFileAfterSend(true);
                    }),
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
        
        // Admin gereja dan admin komisi hanya bisa lihat jemaat di gereja mereka
        return parent::getEloquentQuery()->where('church_id', $user->church_id);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
