# Multi-Church Architecture Setup

## Overview
Aplikasi manajemen gereja telah dikembangkan menjadi multi-church architecture yang memungkinkan satu platform digunakan oleh banyak gereja dengan data yang terpisah namun tetap terintegrasi.

## Struktur Database

### Tabel Utama
1. **churches** - Data gereja
2. **region_types** - Tipe wilayah (Blok, Pepanthan, Sektor, dll)
3. **regions** - Wilayah dengan hierarki
4. **members** - Data jemaat
5. **member_region** - Relasi many-to-many jemaat dan wilayah
6. **users** - User dengan church_id untuk multi-church

### Relasi
- Church → RegionType (1:N)
- Church → Region (1:N)
- Church → Member (1:N)
- Church → User (1:N)
- RegionType → Region (1:N)
- Region → Region (1:N) [Hierarchical]
- Member → Region (N:N)

## Role dan Permission

### Role
- **superadmin**: Akses penuh ke semua gereja
- **admin_gereja**: Admin lokal gereja
- **admin_komisi**: Admin komisi gereja
- **jemaat**: Jemaat biasa

### Permission
- Superadmin: CRUD semua data di semua gereja
- Admin Gereja: CRUD data di gereja mereka saja
- Admin Komisi: Read/Update data di gereja mereka
- Jemaat: Read data di gereja mereka

## Setup dan Instalasi

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Jalankan Seeder
```bash
php artisan db:seed
```

### 3. Data Dummy yang Dibuat
- 3 Gereja (GKJ Prambanan, GKJ Gondokusuman, GKJ Wirobrajan)
- Tipe wilayah untuk setiap gereja
- Wilayah dengan hierarki
- 20 jemaat per gereja
- User superadmin dan admin gereja

### 4. Login Credentials
- **Superadmin**: superadmin@example.com / password
- **Admin GKJ Prambanan**: admin@gkjprambanan.org / password
- **Admin GKJ Gondokusuman**: admin@gkjgondokusuman.org / password
- **Admin GKJ Wirobrajan**: admin@gkjwirobrajan.org / password

## Fitur Multi-Church

### 1. Konfigurasi Wilayah Fleksibel
Setiap gereja dapat memiliki struktur wilayah yang berbeda:
- GKJ Prambanan: Blok → Pepanthan
- GKJ Gondokusuman: Sektor → Lingkungan
- GKJ Wirobrajan: Wilayah → Kelompok

### 2. Data Isolation
- Setiap gereja memiliki data yang terpisah
- Admin gereja hanya bisa akses data gereja mereka
- Superadmin bisa akses semua data

### 3. Hierarchical Regions
- Wilayah dapat memiliki parent-child relationship
- Support untuk multiple level hierarki
- Jemaat dapat terdaftar di multiple wilayah

### 4. Member Management
- Data lengkap jemaat (baptis, sidi, dll)
- Relasi many-to-many dengan wilayah
- Filter dan search berdasarkan gereja

## Filament Resources

### Church Resource
- CRUD gereja
- Upload logo
- Konfigurasi gereja
- Filter berdasarkan status aktif

### RegionType Resource
- CRUD tipe wilayah
- Level hierarki
- Filter berdasarkan gereja

### Region Resource
- CRUD wilayah
- Hierarchical structure
- Filter berdasarkan gereja dan tipe

### Member Resource
- CRUD jemaat
- Upload foto
- Status baptis dan sidi
- Relasi dengan wilayah
- Filter berdasarkan gereja

## Helper Functions

### ChurchHelper
```php
// Get current church
$church = current_church();

// Get current church ID
$churchId = current_church_id();

// Check access
$canAccess = can_access_church($churchId);

// Filter query
$query = ChurchHelper::filterByChurch($query);
```

## Middleware

### MultiChurchMiddleware
- Set church context untuk user
- Filter data berdasarkan gereja user
- Bypass untuk superadmin

## Policy

### ChurchPolicy
- Superadmin: Full access
- Admin Gereja: Access to their church only

### MemberPolicy
- Superadmin: Full access
- Admin Gereja: Access to their church members only
- Admin Komisi: Read/Update access to their church members

## Customization

### Menambah Gereja Baru
1. Login sebagai superadmin
2. Buat gereja baru di Church Resource
3. Buat tipe wilayah sesuai kebutuhan
4. Buat wilayah sesuai struktur
5. Buat admin gereja baru

### Menambah Tipe Wilayah
1. Login sebagai admin gereja
2. Buat tipe wilayah baru di RegionType Resource
3. Set level hierarki
4. Buat wilayah sesuai tipe baru

## Best Practices

1. **Data Consistency**: Selalu gunakan church_id untuk filter data
2. **Permission Check**: Gunakan policy untuk authorization
3. **Helper Functions**: Gunakan helper untuk consistency
4. **Middleware**: Pastikan middleware di-register dengan benar
5. **Seeding**: Gunakan seeder untuk data awal yang konsisten

## Troubleshooting

### Issue: Data tidak ter-filter berdasarkan gereja
**Solution**: Pastikan middleware MultiChurchMiddleware sudah di-register di Kernel.php

### Issue: User tidak bisa akses data gereja mereka
**Solution**: Check church_id di tabel users dan pastikan policy sudah benar

### Issue: Relasi tidak berfungsi
**Solution**: Pastikan foreign key constraint sudah benar di migration

## Next Steps

1. **Frontend Public**: Buat halaman public untuk daftar gereja
2. **Member Registration**: Form pendaftaran jemaat online
3. **Dashboard**: Dashboard khusus per gereja
4. **Reporting**: Laporan per gereja
5. **API**: REST API untuk mobile app
6. **Notification**: Sistem notifikasi per gereja
