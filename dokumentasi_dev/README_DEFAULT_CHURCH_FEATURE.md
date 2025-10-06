# Fitur Gereja Default (Halaman Depan)

## Deskripsi
Fitur ini memungkinkan superadmin untuk memilih gereja mana yang akan ditampilkan sebagai halaman depan website. Ketika ada gereja yang dipilih sebagai default, halaman utama (/) akan menampilkan company profile gereja tersebut alih-alih daftar gereja.

## Fitur yang Ditambahkan

### 1. Database Schema
- **Kolom Baru**: `is_default` (boolean) di tabel `churches`
- **Migration**: `2025_10_06_133411_add_default_church_id_to_churches_table.php`
- **Constraint**: Hanya satu gereja yang bisa menjadi default pada satu waktu

### 2. Model Church
- **Field**: `is_default` ditambahkan ke `$fillable` dan `$casts`
- **Scope**: `scopeDefault()` untuk query gereja default
- **Boot Method**: Logic untuk memastikan hanya satu gereja default

### 3. Admin Panel (Filament)
- **Form Field**: Toggle "Gereja Default (Halaman Depan)" hanya visible untuk superadmin
- **Table Column**: Kolom "Default" dengan icon bintang untuk superadmin
- **Helper Text**: Penjelasan bahwa gereja ini akan ditampilkan sebagai halaman depan

### 4. Controller Logic
- **ChurchController::index()**: 
  - Cek apakah ada gereja default
  - Jika ada, tampilkan company profile gereja tersebut
  - Jika tidak ada, tampilkan daftar gereja
- **ChurchController::list()**: Method baru untuk menampilkan daftar gereja secara manual

### 5. Routes
- **Homepage (/)**: Menampilkan gereja default atau daftar gereja
- **Daftar Gereja (/churches)**: Menampilkan daftar semua gereja
- **Detail Gereja (/churches/{church})**: Menampilkan company profile gereja

### 6. Views
- **Company Profile**: Halaman `churches/show.blade.php` diubah menjadi website company profile yang menarik
- **Daftar Gereja**: Menambahkan indikator gereja default dengan badge "Halaman Depan"
- **Informasi Fitur**: Notifikasi tentang fitur baru di halaman daftar gereja

## Cara Penggunaan

### Untuk Superadmin:
1. Login ke admin panel (`/admin`)
2. Buka menu "Gereja"
3. Edit gereja yang ingin dijadikan halaman depan
4. Aktifkan toggle "Gereja Default (Halaman Depan)"
5. Simpan perubahan

### Untuk Pengunjung:
- **Jika ada gereja default**: Halaman utama akan menampilkan company profile gereja tersebut
- **Jika tidak ada gereja default**: Halaman utama akan menampilkan daftar gereja
- **Akses manual**: Bisa mengakses `/churches` untuk melihat daftar semua gereja

## Company Profile Features

Halaman company profile gereja default mencakup:

### 1. Header
- Logo dan nama gereja
- Navigation menu (Tentang, Kontak, Layanan)
- Link ke daftar gereja dan admin login

### 2. Hero Section
- Logo besar gereja
- Nama dan lokasi gereja
- Deskripsi singkat
- Kontak langsung (telepon, email, website)

### 3. About Section
- Informasi tentang gereja
- 3 pilar utama: Komunitas, Pelayanan, Iman

### 4. Services Section
- Layanan gereja (Ibadah Minggu, Sekolah Minggu, Paduan Suara, Pelayanan Sosial)
- Jadwal kegiatan

### 5. Contact Section
- Informasi kontak lengkap
- Quick actions (Login Admin, Website Resmi, Daftar Gereja)

### 6. Footer
- Informasi gereja
- Kontak
- Link akses cepat

## Technical Details

### Database Changes
```sql
ALTER TABLE churches ADD COLUMN is_default BOOLEAN DEFAULT FALSE;
```

### Model Logic
```php
// Boot method untuk memastikan hanya satu gereja default
protected static function boot()
{
    parent::boot();
    
    static::saving(function ($church) {
        if ($church->is_default) {
            static::where('id', '!=', $church->id)->update(['is_default' => false]);
        }
    });
}
```

### Controller Logic
```php
public function index()
{
    $defaultChurch = Church::active()->default()->first();
    
    if ($defaultChurch) {
        return $this->show($defaultChurch);
    }
    
    $churches = Church::active()->orderBy('name')->get();
    return view('churches.index', compact('churches'));
}
```

## Benefits

1. **Professional Website**: Gereja default mendapat website company profile yang profesional
2. **Easy Management**: Superadmin dapat dengan mudah mengubah gereja default
3. **User Experience**: Pengunjung langsung melihat informasi gereja utama
4. **Flexibility**: Sistem tetap mendukung multi-church dengan fallback ke daftar gereja
5. **SEO Friendly**: Halaman company profile lebih SEO-friendly untuk gereja default

## Future Enhancements

1. **Custom Content**: Memungkinkan gereja menambahkan konten custom (gallery, berita, dll)
2. **Event Calendar**: Menampilkan jadwal kegiatan gereja
3. **Online Giving**: Integrasi dengan sistem donasi online
4. **Sermon Archive**: Arsip khotbah dan video ibadah
5. **Member Portal**: Portal khusus untuk jemaat gereja default
