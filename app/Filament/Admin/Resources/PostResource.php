<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PostResource\Pages;
use App\Models\Post;
use App\Models\Category;
use App\Models\Church;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Data Posting';

    protected static ?string $modelLabel = 'Posting';

    protected static ?string $pluralModelLabel = 'Data Posting';

    protected static ?string $navigationGroup = 'Post';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255),
                
                Textarea::make('excerpt')
                    ->label('Ringkasan')
                    ->maxLength(500)
                    ->rows(3),
                
                RichEditor::make('content')
                    ->label('Konten')
                    ->required()
                    ->columnSpanFull(),
                
                Select::make('category_id')
                    ->label('Kategori')
                    ->options(Category::active()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                
                FileUpload::make('featured_image')
                    ->label('Gambar Utama')
                    ->image()
                    ->directory('posts')
                    ->visibility('public')
                    ->maxSize(2048),
                
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ])
                    ->default('draft')
                    ->required(),
                
                Toggle::make('is_featured')
                    ->label('Featured Post')
                    ->default(false),
                
                Select::make('church_id')
                    ->label('Gereja')
                    ->options(Church::pluck('name', 'id'))
                    ->required()
                    ->default(auth()->user()->church_id)
                    ->disabled(auth()->user()->isAdminGereja()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Gambar')
                    ->circular()
                    ->size(40),
                
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (Post $record): string => $record->category->color ?? 'gray'),
                
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                        'archived' => 'danger',
                    }),
                
                ToggleColumn::make('is_featured')
                    ->label('Featured'),
                
                TextColumn::make('church.name')
                    ->label('Gereja')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('user.name')
                    ->label('Penulis')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('views')
                    ->label('Views')
                    ->numeric()
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),
                
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                
                SelectFilter::make('church_id')
                    ->label('Gereja')
                    ->relationship('church', 'name')
                    ->visible(fn (): bool => auth()->user()->isSuperAdmin()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn (Post $record): bool => 
                        auth()->user()->isSuperAdmin() || 
                        (auth()->user()->isAdminGereja() && auth()->user()->church_id === $record->church_id)
                    ),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Post $record): bool => 
                        auth()->user()->isAdminGereja() && auth()->user()->church_id === $record->church_id
                    ),
                
                // Toggle Status Action (for Superadmin only)
                Action::make('toggleStatus')
                    ->label(fn (Post $record): string => $record->status === 'published' ? 'Non Aktif' : 'Aktif')
                    ->color(fn (Post $record): string => $record->status === 'published' ? 'danger' : 'success')
                    ->icon(fn (Post $record): string => $record->status === 'published' ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->visible(fn (): bool => auth()->user()->isSuperAdmin())
                    ->action(function (Post $record) {
                        $newStatus = $record->status === 'published' ? 'archived' : 'published';
                        $record->update(['status' => $newStatus]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Status berhasil diubah')
                            ->body("Posting diubah menjadi " . ($newStatus === 'published' ? 'Aktif' : 'Non Aktif'))
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()->isAdminGereja()),
                ]),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        // Admin gereja hanya dapat melihat posting gerejanya
        if (auth()->user()->isAdminGereja()) {
            $query->where('church_id', auth()->user()->church_id);
        }
        
        return $query;
    }

    public static function canCreate(): bool
    {
        return auth()->user()->isAdminGereja();
    }
}
