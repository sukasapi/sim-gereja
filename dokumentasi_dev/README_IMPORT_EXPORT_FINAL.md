# Fitur Import & Export Jemaat - Final Documentation

## 🎯 Overview
Dokumentasi ini menjelaskan implementasi fitur import dan export jemaat yang telah diatur ulang sesuai permintaan user, termasuk menu import sebagai sub menu, role-based access control, dan export sebagai tombol di tabel.

## ✅ Fitur yang Telah Diimplementasikan

### 1. **Menu Import Jemaat sebagai Sub Menu**
- ✅ **Navigation Group**: "Data Jemaat"
- ✅ **Navigation Label**: "Import Jemaat"
- ✅ **Navigation Sort**: 2 (setelah Data Jemaat)
- ✅ **Icon**: `heroicon-o-arrow-up-tray`

### 2. **Role-based Access Control**
- ✅ **Superadmin**: Dapat mengimport data jemaat untuk semua gereja
- ✅ **Admin Gereja**: Hanya dapat mengimport data jemaat dari gerejanya sendiri
- ✅ **Validation**: Validasi akses gereja di form dan processing

### 3. **Fitur Export sebagai Tombol di Tabel**
- ✅ **Location**: Tombol "Export CSV" di header tabel Data Jemaat
- ✅ **Icon**: `heroicon-o-arrow-down-tray`
- ✅ **Color**: Info (biru)
- ✅ **Role-based**: Filter data berdasarkan gereja user

## 📊 Database Structure

### Table: `member_imports`
```sql
CREATE TABLE member_imports (
    id BIGINT PRIMARY KEY,
    church_id BIGINT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_size BIGINT NOT NULL,
    total_rows INT DEFAULT 0,
    imported_rows INT DEFAULT 0,
    skipped_rows INT DEFAULT 0,
    error_rows INT DEFAULT 0,
    errors JSON NULL,
    status VARCHAR(255) DEFAULT 'pending',
    imported_by BIGINT NOT NULL,
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (church_id) REFERENCES churches(id) ON DELETE CASCADE,
    FOREIGN KEY (imported_by) REFERENCES users(id) ON DELETE CASCADE
);
```

### Model: `MemberImport`
```php
class MemberImport extends Model
{
    protected $fillable = [
        'church_id', 'filename', 'file_path', 'file_size',
        'total_rows', 'imported_rows', 'skipped_rows', 'error_rows',
        'errors', 'status', 'imported_by', 'started_at', 'completed_at'
    ];

    protected $casts = [
        'errors' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'file_size' => 'integer',
    ];

    // Relationships
    public function church(): BelongsTo
    public function importedBy(): BelongsTo
    
    // Scopes
    public function scopePending($query)
    public function scopeCompleted($query)
    public function scopeFailed($query)
}
```

## 🎨 Menu Organization

### Before (Old Structure)
```
Data Jemaat
├── Data Jemaat
├── Laporan Jemaat
├── Statistik Jemaat
└── Export Jemaat
```

### After (New Structure)
```
Data Jemaat
├── Data Jemaat (with Export CSV button)
└── Import Jemaat (sub menu)
```

## 🔧 Import Functionality

### 1. **MemberImportResource Configuration**
```php
class MemberImportResource extends Resource
{
    protected static ?string $navigationGroup = 'Data Jemaat';
    protected static ?string $navigationLabel = 'Import Jemaat';
    protected static ?string $navigationSort = 2;
    
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        
        // Filter by church for non-superadmin
        if ($user && !$user->isSuperAdmin() && $user->church_id) {
            $query->where('church_id', $user->church_id);
        }
        
        return $query;
    }
}
```

### 2. **Form Configuration**
```php
Forms\Components\Section::make('Upload File CSV')
    ->schema([
        Forms\Components\Select::make('church_id')
            ->label('Gereja')
            ->relationship('church', 'name')
            ->required()
            ->default(fn () => auth()->user()->church_id)
            ->disabled(fn () => !auth()->user()->isSuperAdmin())
            ->helperText('Superadmin dapat memilih gereja manapun, admin gereja hanya dapat memilih gerejanya sendiri'),
        
        Forms\Components\FileUpload::make('csv_file')
            ->label('File CSV')
            ->acceptedFileTypes(['text/csv', 'application/csv'])
            ->required()
            ->helperText('Format CSV: nama, tanggal_lahir, jenis_kelamin, alamat, telepon, email, wilayah, ayah, ibu, tanggal_gabung, status_baptis, status_sidi')
            ->disk('local')
            ->directory('imports')
            ->visibility('private'),
        
        Forms\Components\Toggle::make('skip_duplicates')
            ->label('Skip Duplikat')
            ->helperText('Lewati baris yang sudah ada berdasarkan nama dan tanggal lahir')
            ->default(true),
    ])
```

### 3. **Import Processing**
```php
protected function processImport(): void
{
    $import = $this->record;
    
    try {
        // Update status to processing
        $import->update(['status' => 'processing']);
        
        // Parse CSV
        $csvData = array_map('str_getcsv', file($filePath));
        $header = array_shift($csvData);
        
        $imported = 0;
        $skipped = 0;
        $errors = [];
        
        foreach ($csvData as $index => $row) {
            // Process each row
            // - Validate data
            // - Find relationships (region, father, mother)
            // - Create member
            // - Handle errors
        }
        
        // Update import record
        $import->update([
            'status' => 'completed',
            'imported_rows' => $imported,
            'skipped_rows' => $skipped,
            'error_rows' => count($errors),
            'errors' => $errors,
            'completed_at' => now(),
        ]);
        
    } catch (\Exception $e) {
        // Handle errors
        $import->update([
            'status' => 'failed',
            'error_rows' => 1,
            'errors' => [$e->getMessage()],
            'completed_at' => now(),
        ]);
    }
}
```

## 📤 Export Functionality

### 1. **Export Button in Table Header**
```php
->headerActions([
    Tables\Actions\Action::make('export_csv')
        ->label('Export CSV')
        ->icon('heroicon-o-arrow-down-tray')
        ->color('info')
        ->action(function () {
            // Export logic
        }),
])
```

### 2. **Export Logic**
```php
$user = auth()->user();
$query = \App\Models\Member::query();

// Filter berdasarkan gereja untuk non-superadmin
if (!$user->isSuperAdmin() && $user->church_id) {
    $query->where('church_id', $user->church_id);
}

$members = $query->with(['church', 'region', 'father', 'mother'])->get();

// Generate CSV
$filename = 'export_jemaat_' . now()->format('Y-m-d_H-i-s') . '.csv';
$filepath = storage_path('app/exports/' . $filename);

// Write CSV data
$file = fopen($filepath, 'w');
fputcsv($file, [
    'nama', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'telepon', 'email',
    'gereja', 'wilayah', 'ayah', 'ibu', 'tanggal_gabung', 'status_baptis',
    'status_sidi', 'catatan_pelayanan', 'catatan_umum', 'status_aktif'
]);

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

return response()->download($filepath)->deleteFileAfterSend(true);
```

## 🔐 Role-based Access Control

### 1. **Superadmin Access**
- ✅ **Import**: Dapat memilih gereja manapun untuk import
- ✅ **Export**: Dapat export data dari semua gereja
- ✅ **View**: Dapat melihat semua import records

### 2. **Admin Gereja Access**
- ✅ **Import**: Hanya dapat import ke gerejanya sendiri
- ✅ **Export**: Hanya dapat export data gerejanya sendiri
- ✅ **View**: Hanya dapat melihat import records gerejanya sendiri

### 3. **Validation Logic**
```php
// In CreateMemberImport::mutateFormDataBeforeCreate()
if (!$user->isSuperAdmin() && $data['church_id'] != $user->church_id) {
    throw new \Exception('Anda tidak memiliki akses untuk mengimpor data ke gereja ini.');
}

// In MemberImportResource::getEloquentQuery()
if ($user && !$user->isSuperAdmin() && $user->church_id) {
    $query->where('church_id', $user->church_id);
}
```

## 📋 Import Status Tracking

### Status Types
- **pending**: Import belum dimulai
- **processing**: Import sedang berlangsung
- **completed**: Import berhasil selesai
- **failed**: Import gagal

### Tracking Fields
- **total_rows**: Total baris dalam CSV
- **imported_rows**: Jumlah baris yang berhasil diimpor
- **skipped_rows**: Jumlah baris yang dilewati (duplikat)
- **error_rows**: Jumlah baris yang error
- **errors**: Array detail error
- **started_at**: Waktu mulai import
- **completed_at**: Waktu selesai import

## 🧪 Testing Results

### Test Scripts
- ✅ `test_import_export_features.php` - Comprehensive test

### Test Results
```
✅ MemberImport Model: COMPLETED
✅ MemberImportResource: COMPLETED
✅ Import Functionality: COMPLETED
✅ Export Functionality: COMPLETED
✅ Menu Organization: COMPLETED
✅ All Features: WORKING PERFECTLY! 🚀
```

## 📁 Files Created/Modified

### New Files
- ✅ `app/Models/MemberImport.php`
- ✅ `app/Filament/Admin/Resources/MemberImportResource.php`
- ✅ `app/Filament/Admin/Resources/MemberImportResource/Pages/CreateMemberImport.php`
- ✅ `database/migrations/2025_10_06_090809_create_member_imports_table.php`
- ✅ `test_import_export_features.php`
- ✅ `README_IMPORT_EXPORT_FINAL.md`

### Modified Files
- ✅ `app/Filament/Admin/Resources/MemberResource.php` - Removed import action, added export action

## 🚀 How to Use

### 1. **Import Data Jemaat**
1. Buka menu "Data Jemaat" → "Import Jemaat"
2. Pilih gereja (superadmin dapat memilih semua, admin gereja hanya gerejanya)
3. Upload file CSV
4. Tentukan skip duplikat
5. Klik "Create"
6. Monitor status import di tabel

### 2. **Export Data Jemaat**
1. Buka menu "Data Jemaat"
2. Klik tombol "Export CSV" di header tabel
3. File CSV akan otomatis didownload
4. File berisi semua data jemaat sesuai akses user

### 3. **Monitor Import Status**
1. Buka menu "Data Jemaat" → "Import Jemaat"
2. Lihat status import di tabel
3. Klik "View" untuk melihat detail error (jika ada)

## 🎯 User Requirements Fulfilled

### ✅ **Menu Import sebagai Sub Menu**
- **Request**: "buatkan menu import jemaat sebagai sub menu data jemaat"
- **Implementation**: MemberImportResource dengan navigationGroup 'Data Jemaat'

### ✅ **Role-based Import Access**
- **Request**: "jika superadmin maka dapat menimport data jemaat untuk semua gereja sedangkan untuk admin gereja hanya dapat mengimport data jemaat dari gerejanya saja"
- **Implementation**: Validation di form dan processing, filtering di query

### ✅ **Export sebagai Tombol di Tabel**
- **Request**: "pindahkan fitur export jemaat sebagai tombol di tabel data jemaat"
- **Implementation**: Export action di headerActions MemberResource

## 🎉 Summary

**Semua fitur yang diminta telah berhasil diimplementasikan!**

### ✅ **Completed Features:**
1. **Menu Import as Sub Menu** - Import Jemaat sebagai sub menu Data Jemaat
2. **Role-based Import Access** - Superadmin (semua gereja), Admin Gereja (gereja sendiri)
3. **Export as Table Button** - Export CSV sebagai tombol di header tabel
4. **Import Status Tracking** - Tracking lengkap status import
5. **Error Handling** - Handling error dan notifikasi
6. **File Management** - Upload dan auto-delete file

### 🎯 **User Requirements:**
- ✅ Menu import jemaat sebagai sub menu data jemaat
- ✅ Superadmin dapat import untuk semua gereja
- ✅ Admin gereja hanya dapat import untuk gerejanya sendiri
- ✅ Fitur export dipindahkan ke tombol di tabel data jemaat

**Aplikasi Multi-Church Architecture sekarang memiliki sistem import/export jemaat yang terorganisir dengan baik sesuai permintaan!** 🚀
