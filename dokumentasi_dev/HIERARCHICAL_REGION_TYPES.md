# Hierarchical Region Types

## Overview
Fitur ini memungkinkan tipe wilayah untuk memiliki struktur hierarkis (parent-child relationship), sehingga dapat membuat sub wilayah seperti: Pepanthan → Blok → Lingkungan.

## Fitur yang Tersedia

### 1. Parent-Child Relationship
- Tipe wilayah dapat memiliki parent (induk)
- Tipe wilayah dapat memiliki children (anak)
- Relasi many-to-many dengan regions

### 2. Auto-Level Calculation
- Level otomatis dihitung berdasarkan parent
- Level 1 = Root (tidak punya parent)
- Level 2 = Child dari Level 1
- Level 3 = Child dari Level 2

### 3. Full Path Display
- Menampilkan path lengkap: "Pepanthan → Blok → Lingkungan"
- Accessor `full_path` untuk mendapatkan path lengkap

### 4. Sort Order
- Field `sort_order` untuk mengurutkan tipe wilayah
- Default sorting berdasarkan `sort_order`

### 5. Recursive Children Display
- Method `getAllDescendants()` untuk mendapatkan semua descendants
- Scope `root()`, `children()`, `active()`

## Struktur Database

### Migration: `add_parent_id_to_region_types_table`
```php
$table->foreignId('parent_id')->nullable()->constrained('region_types')->onDelete('cascade');
$table->integer('sort_order')->default(0);
```

### Model: `RegionType`
```php
// Relationships
public function parent(): BelongsTo
public function children(): HasMany
public function regions(): HasMany

// Scopes
public function scopeRoot($query)
public function scopeChildren($query, $parentId)
public function scopeActive($query)

// Accessors
public function getFullPathAttribute()

// Methods
public function getAllDescendants()
```

## Filament Resource

### Form Fields
- **Tipe Wilayah Induk**: Select field dengan relationship ke parent
- **Level**: Auto-calculated berdasarkan parent (disabled jika ada parent)
- **Urutan**: Sort order field
- **Path Lengkap**: Display field untuk full path

### Table Columns
- **Tipe Induk**: Menampilkan parent name
- **Level**: Level hierarki
- **Urutan**: Sort order
- **Path Lengkap**: Full path display
- **Jumlah Wilayah**: Count regions

### Filters
- Filter berdasarkan parent
- Filter berdasarkan status aktif
- Filter berdasarkan gereja

## Data Structure

### GKJ Prambanan
```
Pepanthan (Level 1)
└── Blok (Level 2)
    └── Lingkungan (Level 3)
```

### GKJ Gondokusuman
```
Sektor (Level 1)
└── Lingkungan (Level 2)
    └── RT (Level 3)
```

### GKJ Wirobrajan
```
Wilayah (Level 1)
└── Kelompok (Level 2)
    └── Sub Kelompok (Level 3)
```

## Usage Examples

### 1. Get Root Region Types
```php
$rootTypes = $church->regionTypes()->root()->get();
```

### 2. Get Children of a Region Type
```php
$children = $regionType->children()->get();
```

### 3. Get Full Path
```php
echo $regionType->full_path; // "Pepanthan → Blok → Lingkungan"
```

### 4. Get All Descendants
```php
$descendants = $regionType->getAllDescendants();
```

### 5. Create Hierarchical Region Type
```php
RegionType::create([
    'church_id' => 1,
    'name' => 'Blok',
    'slug' => 'blok',
    'level' => 2,
    'parent_id' => $pepanthan->id,
    'sort_order' => 1,
    'is_active' => true,
]);
```

## Admin Panel Usage

1. **Buka Admin Panel**: http://127.0.0.1:8000/admin
2. **Login sebagai superadmin**
3. **Buka Tipe Wilayah**
4. **Lihat hierarchical structure** di tabel
5. **Test create/edit** tipe wilayah dengan parent
6. **Lihat full path** di kolom "Path Lengkap"

## Benefits

1. **Fleksibilitas**: Setiap gereja dapat memiliki struktur wilayah yang berbeda
2. **Skalabilitas**: Dapat menambah level hierarki sesuai kebutuhan
3. **Konsistensi**: Auto-level calculation memastikan konsistensi data
4. **User Experience**: Full path display memudahkan navigasi
5. **Maintenance**: Sort order memudahkan pengurutan dan maintenance

## Technical Notes

- **Foreign Key Constraint**: `parent_id` memiliki constraint ke `region_types.id`
- **Cascade Delete**: Jika parent dihapus, children akan dihapus juga
- **Unique Constraint**: `church_id` + `slug` tetap unique
- **Performance**: Index pada `parent_id` untuk query yang efisien
- **Validation**: Level otomatis dihitung, tidak bisa diubah manual jika ada parent

## Future Enhancements

1. **Drag & Drop**: Interface untuk mengubah hierarki dengan drag & drop
2. **Bulk Operations**: Import/export hierarchical structure
3. **Visual Tree**: Tree view untuk menampilkan hierarki
4. **Advanced Filtering**: Filter berdasarkan level atau path
5. **Audit Trail**: Log perubahan hierarki
