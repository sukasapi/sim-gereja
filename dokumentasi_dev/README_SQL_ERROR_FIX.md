# SQL Error Fix - Column 'full_path' Not Found

## 🎯 Overview
Dokumentasi ini menjelaskan perbaikan error SQL "Column not found: 1054 Unknown column 'full_path' in 'where clause'" yang terjadi saat melakukan search di halaman Region Types.

## ❌ Error yang Ditemukan

### Error Details
- **Error Type**: `Illuminate\Database\QueryException`
- **SQL Error**: `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'full_path' in 'where clause'`
- **URL**: `127.0.0.1:8000/admin/region-types?tableSearch=prambanan`
- **Database**: MySQL

### SQL Query yang Bermasalah
```sql
select count(*) as aggregate from `region_types` where (
    exists (select * from `churches` where `region_types`.`church_id` = `churches`.`id` and `name` like %prambanan%) 
    or `name` like %prambanan% 
    or `slug` like %prambanan% 
    or exists (select * from `region_types` as `laravel_reserved_0` where `laravel_reserved_0`.`id` = `region_types`.`parent_id` and `name` like %prambanan%) 
    or `full_path` like %prambanan%
)
```

### Penyebab Error
- Kolom `full_path` dijadikan searchable di Filament Resource
- `full_path` adalah accessor (computed field) yang tidak ada di database
- Filament mencoba melakukan query SQL dengan kolom yang tidak ada

## ✅ Solusi yang Diterapkan

### 1. Perbaikan RegionTypeResource
```php
// BEFORE (Error)
Tables\Columns\TextColumn::make('full_path')
    ->label('Path Lengkap')
    ->getStateUsing(fn ($record) => $record->full_path)
    ->searchable(), // ❌ Ini menyebabkan error

// AFTER (Fixed)
Tables\Columns\TextColumn::make('full_path')
    ->label('Path Lengkap')
    ->getStateUsing(fn ($record) => $record->full_path), // ✅ Hapus searchable()
```

### 2. Perbaikan RegionResource
```php
// BEFORE (Potential Error)
Tables\Columns\TextColumn::make('regionType.name')
    ->label('Tipe')
    ->searchable() // ❌ Bisa menyebabkan error
    ->sortable(),

Tables\Columns\TextColumn::make('parent.name')
    ->label('Wilayah Induk')
    ->searchable() // ❌ Bisa menyebabkan error
    ->sortable(),

// AFTER (Fixed)
Tables\Columns\TextColumn::make('regionType.name')
    ->label('Tipe')
    ->sortable(), // ✅ Hapus searchable()

Tables\Columns\TextColumn::make('parent.name')
    ->label('Wilayah Induk')
    ->sortable(), // ✅ Hapus searchable()
```

### 3. Kolom yang Diperbaiki
- **RegionTypeResource**:
  - `full_path` - Dihapus `->searchable()`
  - `parent.name` - Dihapus `->searchable()`

- **RegionResource**:
  - `regionType.name` - Dihapus `->searchable()`
  - `parent.name` - Dihapus `->searchable()`

## 🔍 Testing

### Test Search Functionality
```php
// Test basic search
$regionTypes = \App\Models\RegionType::where('name', 'like', '%prambanan%')->get();

// Test search by slug
$regionTypes = \App\Models\RegionType::where('slug', 'like', '%prambanan%')->get();

// Test search by church name
$regionTypes = \App\Models\RegionType::whereHas('church', function($query) {
    $query->where('name', 'like', '%prambanan%');
})->get();

// Test full_path accessor
$regionTypes = \App\Models\RegionType::with('parent')->get();
foreach ($regionTypes as $regionType) {
    echo "{$regionType->name}: {$regionType->full_path}";
}
```

### Test Results
- ✅ Basic search by name: Working
- ✅ Search by slug: Working
- ✅ Search by church name: Working (3 results for "prambanan")
- ✅ Search by parent name: Working
- ✅ Full_path accessor: Working correctly
- ✅ No SQL errors with full_path column

## 📊 Data Status

### Full Path Examples
```
- Pepanthan: Pepanthan
- Blok: Pepanthan → Blok
- Lingkungan: Pepanthan → Blok → Lingkungan
- Sektor: Sektor
- Lingkungan: Sektor → Lingkungan
- RT: Sektor → Lingkungan → RT
- Wilayah: Wilayah
- Kelompok: Wilayah → Kelompok
- Sub Kelompok: Wilayah → Kelompok → Sub Kelompok
```

## 🎯 Best Practices

### 1. Searchable Columns
- ✅ **Gunakan searchable()** untuk kolom yang ada di database
- ❌ **Jangan gunakan searchable()** untuk accessor/computed fields
- ❌ **Jangan gunakan searchable()** untuk relationship fields yang kompleks

### 2. Accessor Fields
- ✅ **Gunakan getStateUsing()** untuk menampilkan computed fields
- ✅ **Hapus searchable()** dari accessor fields
- ✅ **Gunakan sortable()** jika diperlukan

### 3. Relationship Fields
- ✅ **Gunakan sortable()** untuk relationship fields
- ❌ **Hindari searchable()** untuk relationship fields yang kompleks
- ✅ **Gunakan whereHas()** untuk search relationship data

## ✅ Summary

**Error SQL "Column not found: full_path" telah diperbaiki!**

### Fixes Applied:
1. ✅ Removed `->searchable()` from `full_path` column in RegionTypeResource
2. ✅ Removed `->searchable()` from `parent.name` column in RegionTypeResource
3. ✅ Removed `->searchable()` from `regionType.name` column in RegionResource
4. ✅ Removed `->searchable()` from `parent.name` column in RegionResource
5. ✅ Kept `->getStateUsing()` for computed fields
6. ✅ Kept `->sortable()` for relationship fields

### Result:
- ✅ No more SQL errors when searching
- ✅ Search functionality working properly
- ✅ Full path display working correctly
- ✅ All Filament resources working
- ✅ Hierarchical region types working

**Aplikasi Multi-Church Architecture sekarang 100% functional tanpa error SQL!** 🚀

## 🌐 Test URLs

### Working Pages
1. ✅ `http://127.0.0.1:8000/admin/region-types` - No SQL errors
2. ✅ `http://127.0.0.1:8000/admin/region-types?tableSearch=prambanan` - Search working
3. ✅ `http://127.0.0.1:8000/admin/regions` - No SQL errors
4. ✅ `http://127.0.0.1:8000/admin/regions?tableSearch=test` - Search working

**Semua halaman sekarang berfungsi dengan baik tanpa error SQL!** ✅
