# Multi-Church Architecture - Aplikasi Manajemen Gereja

## 🏛️ Overview

Aplikasi manajemen gereja yang telah dikembangkan menjadi **multi-church architecture**, memungkinkan satu platform digunakan oleh banyak gereja dengan data yang terpisah namun tetap terintegrasi.

## ✨ Fitur Utama

### 🏢 Multi-Church Support
- **Satu platform, banyak gereja**: Setiap gereja memiliki data yang terpisah
- **Konfigurasi fleksibel**: Setiap gereja dapat memiliki struktur wilayah yang berbeda
- **Role-based access**: Superadmin, Admin Gereja, Admin Komisi, dan Jemaat

### 👥 Manajemen Jemaat
- Data lengkap jemaat (nama, alamat, kontak, foto)
- Status baptis dan sidi dengan tanggal
- Relasi many-to-many dengan wilayah
- Catatan pelayanan dan catatan umum

### 🗺️ Sistem Wilayah Fleksibel
- **Hierarchical structure**: Wilayah dapat memiliki parent-child relationship
- **Custom region types**: Setiap gereja dapat mendefinisikan tipe wilayah sendiri
  - GKJ Prambanan: Blok → Pepanthan
  - GKJ Gondokusuman: Sektor → Lingkungan
  - GKJ Wirobrajan: Wilayah → Kelompok

### 🔐 Keamanan & Permission
- **Data isolation**: Setiap gereja hanya bisa akses data mereka
- **Role-based permissions**: Berbagai level akses sesuai kebutuhan
- **Policy-based authorization**: Laravel Policy untuk kontrol akses

## 🚀 Quick Start

### 1. Setup Otomatis
```bash
# Jalankan script setup otomatis
php setup_multi_church.php
```

### 2. Setup Manual
```bash
# Jalankan migration
php artisan migrate

# Jalankan seeder
php artisan db:seed

# Buat storage link
php artisan storage:link

# Clear cache
php artisan config:clear
php artisan cache:clear
```

### 3. Akses Aplikasi
- **Public**: http://localhost (daftar gereja)
- **Admin Panel**: http://localhost/admin

## 👤 Login Credentials

### Superadmin
- **Email**: superadmin@example.com
- **Password**: password
- **Akses**: Semua gereja

### Admin Gereja
- **GKJ Prambanan**: admin@gkjprambanan.org / password
- **GKJ Gondokusuman**: admin@gkjgondokusuman.org / password
- **GKJ Wirobrajan**: admin@gkjwirobrajan.org / password
- **Akses**: Hanya gereja mereka

## 📊 Data yang Dibuat

### Gereja (3)
1. **GKJ Prambanan** - Sleman, Yogyakarta
2. **GKJ Gondokusuman** - Yogyakarta
3. **GKJ Wirobrajan** - Yogyakarta

### Tipe Wilayah
- **GKJ Prambanan**: Blok (Level 1) → Pepanthan (Level 2)
- **GKJ Gondokusuman**: Sektor (Level 1) → Lingkungan (Level 2)
- **GKJ Wirobrajan**: Wilayah (Level 1) → Kelompok (Level 2)

### Data Lainnya
- **Wilayah**: 3 wilayah level 1 + 6 wilayah level 2 per gereja
- **Jemaat**: 20 jemaat per gereja (total 60 jemaat)
- **User**: 1 superadmin + 3 admin gereja

## 🏗️ Struktur Database

### Tabel Utama
```sql
churches          -- Data gereja
region_types      -- Tipe wilayah (Blok, Pepanthan, dll)
regions          -- Wilayah dengan hierarki
members          -- Data jemaat
member_region    -- Relasi many-to-many jemaat-wilayah
users            -- User dengan church_id
```

### Relasi
- Church → RegionType (1:N)
- Church → Region (1:N)
- Church → Member (1:N)
- Church → User (1:N)
- RegionType → Region (1:N)
- Region → Region (1:N) [Hierarchical]
- Member → Region (N:N)

## 🎯 Role & Permission

### Superadmin
- ✅ CRUD semua data di semua gereja
- ✅ Buat gereja baru
- ✅ Manage user dan role
- ✅ Akses semua fitur

### Admin Gereja
- ✅ CRUD data di gereja mereka saja
- ✅ Manage jemaat dan wilayah
- ✅ Generate laporan gereja
- ❌ Tidak bisa akses gereja lain

### Admin Komisi
- ✅ Read/Update data di gereja mereka
- ✅ Manage jemaat (terbatas)
- ❌ Tidak bisa hapus data
- ❌ Tidak bisa akses gereja lain

### Jemaat
- ✅ Read data gereja mereka
- ✅ Update profil sendiri
- ❌ Tidak bisa akses data jemaat lain

## 🛠️ Filament Resources

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

## 🔧 Helper Functions

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

## 🛡️ Security Features

### Middleware
- **MultiChurchMiddleware**: Set church context dan filter data

### Policy
- **ChurchPolicy**: Kontrol akses gereja
- **MemberPolicy**: Kontrol akses jemaat

### Data Isolation
- Setiap query otomatis di-filter berdasarkan gereja user
- Superadmin bypass semua filter
- Admin gereja hanya akses data gereja mereka

## 📱 Frontend

### Public Pages
- **Homepage**: Daftar gereja yang terdaftar
- **Church Detail**: Detail informasi gereja
- **Responsive Design**: Mobile-friendly dengan Tailwind CSS

### Admin Panel
- **Filament Admin**: Interface admin yang powerful
- **Role-based UI**: UI menyesuaikan dengan role user
- **Multi-language**: Interface dalam bahasa Indonesia

## 🔄 Workflow

### Menambah Gereja Baru
1. Login sebagai superadmin
2. Buat gereja baru di Church Resource
3. Buat tipe wilayah sesuai kebutuhan
4. Buat wilayah sesuai struktur
5. Buat admin gereja baru

### Menambah Jemaat
1. Login sebagai admin gereja
2. Buat jemaat baru di Member Resource
3. Assign ke wilayah yang sesuai
4. Isi data lengkap (baptis, sidi, dll)

### Mengelola Wilayah
1. Buat tipe wilayah baru (jika diperlukan)
2. Buat wilayah sesuai hierarki
3. Assign jemaat ke wilayah

## 🚀 Next Steps

### Fitur yang Bisa Ditambahkan
1. **Frontend Public**
   - Form pendaftaran jemaat online
   - Portal jemaat untuk update profil
   - Galeri foto kegiatan

2. **Dashboard Analytics**
   - Statistik jemaat per wilayah
   - Grafik pertumbuhan jemaat
   - Laporan keuangan gereja

3. **Mobile App**
   - React Native atau Flutter
   - Push notification
   - Offline capability

4. **Integration**
   - Payment gateway untuk persembahan
   - Email/SMS notification
   - Calendar integration

5. **Advanced Features**
   - Multi-language support
   - Advanced reporting
   - API untuk third-party integration

## 📚 Dokumentasi

- **Setup Guide**: `MULTI_CHURCH_SETUP.md`
- **API Documentation**: (akan dibuat)
- **User Manual**: (akan dibuat)

## 🤝 Contributing

1. Fork repository
2. Buat feature branch
3. Commit changes
4. Push ke branch
5. Buat Pull Request

## 📄 License

Proyek ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk detail.

## 🆘 Support

Jika mengalami masalah atau butuh bantuan:
1. Check dokumentasi
2. Cari di issues
3. Buat issue baru jika belum ada
4. Contact developer

---

**Multi-Church Platform** - Solusi terintegrasi untuk manajemen gereja modern 🏛️
