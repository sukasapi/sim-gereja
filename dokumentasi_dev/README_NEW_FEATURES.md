# Fitur Baru Aplikasi GKJ Prambanan

## Ringkasan Fitur yang Ditambahkan

Aplikasi GKJ Prambanan telah diperbarui dengan beberapa fitur baru yang meningkatkan fungsionalitas dan pengalaman pengguna:

### 1. Fitur Profil User dan Penggantian Password
- **Lokasi**: `/profile`
- **Fitur**:
  - User dapat mengedit profil mereka (nama, email)
  - User dapat mengganti password dengan konfirmasi password lama
  - Validasi keamanan password (minimal 8 karakter)
  - Notifikasi sukses setelah update

### 2. Manajemen Data Gereja (Admin Gereja)
- **Lokasi**: `/admin/edit-church`
- **Fitur**:
  - Admin gereja dapat mengedit informasi gereja
  - Field yang dapat diedit: nama, alamat, kota, provinsi, kode pos, telepon, email, website
  - Upload dan ganti logo gereja
  - Validasi data input
  - Hanya admin gereja yang dapat mengakses

### 3. Reset Password User (Superadmin)
- **Lokasi**: `/admin/users` dan `/admin/users/{user}/reset-password`
- **Fitur**:
  - Superadmin dapat melihat daftar semua user
  - Superadmin dapat reset password user lain
  - Form reset password dengan konfirmasi
  - Validasi keamanan password
  - Hanya superadmin yang dapat mengakses

### 4. Sistem Posting/Berita (CMS Style WordPress)
- **Lokasi**: `/posts`, `/categories`, `/berita`
- **Fitur**:
  - **Manajemen Posting**:
    - Create, Read, Update, Delete posting
    - Upload gambar utama (featured image)
    - Status posting: Draft, Published, Archived
    - Featured post untuk highlight posting penting
    - Counter views otomatis
    - Slug URL otomatis
    - Tanggal publikasi otomatis
  
  - **Manajemen Kategori**:
    - Create, Read, Update, Delete kategori
    - Warna kustom untuk setiap kategori
    - Status aktif/nonaktif kategori
    - Counter jumlah posting per kategori
    - Slug URL otomatis
  
  - **Halaman Publik**:
    - `/berita` - Daftar semua berita publik
    - `/berita/{slug}` - Detail berita
    - `/kategori/{slug}` - Berita berdasarkan kategori
    - Featured posts di halaman utama
    - Sidebar kategori dengan counter
    - Related posts di halaman detail
    - Responsive design dengan Tailwind CSS

## Struktur Database Baru

### Tabel `categories`
```sql
- id (primary key)
- name (nama kategori)
- slug (URL slug)
- description (deskripsi kategori)
- color (warna hex untuk kategori)
- is_active (status aktif/nonaktif)
- created_at, updated_at
```

### Tabel `posts`
```sql
- id (primary key)
- title (judul posting)
- slug (URL slug)
- excerpt (ringkasan posting)
- content (konten lengkap)
- featured_image (gambar utama)
- status (draft/published/archived)
- is_featured (posting unggulan)
- views (jumlah views)
- published_at (tanggal publikasi)
- category_id (foreign key ke categories)
- user_id (foreign key ke users)
- church_id (foreign key ke churches)
- created_at, updated_at
```

## Model dan Relasi

### Model Category
- Relasi `hasMany` ke Post
- Scope `active()` untuk kategori aktif
- Auto-generate slug dari nama

### Model Post
- Relasi `belongsTo` ke Category, User, Church
- Scope `published()`, `featured()`, `forChurch()`
- Auto-generate slug dari title
- Auto-set published_at saat status = published

### Model User (Updated)
- Relasi `hasMany` ke Post
- Method `isSuperAdmin()`, `isAdminGereja()`, dll.

### Model Church (Updated)
- Relasi `hasMany` ke Post
- Support upload logo

## Controller dan Routes

### Controller Baru
- `PostController` - CRUD posting
- `CategoryController` - CRUD kategori
- `AdminController` - Fitur admin (reset password, edit gereja)
- `PublicPostController` - Halaman publik berita

### Routes Baru
```php
// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Superadmin routes
    Route::middleware('superadmin')->group(function () {
        Route::get('/users', [AdminController::class, 'users']);
        Route::get('/users/{user}/reset-password', [AdminController::class, 'showResetPasswordForm']);
        Route::post('/users/{user}/reset-password', [AdminController::class, 'resetPassword']);
    });
    
    // Admin gereja routes
    Route::middleware('admin_gereja')->group(function () {
        Route::get('/edit-church', [AdminController::class, 'editChurch']);
        Route::patch('/edit-church', [AdminController::class, 'updateChurch']);
    });
});

// Post routes
Route::resource('posts', PostController::class);
Route::patch('/posts/{post}/toggle-featured', [PostController::class, 'toggleFeatured']);

// Category routes
Route::resource('categories', CategoryController::class);
Route::patch('/categories/{category}/toggle-active', [CategoryController::class, 'toggleActive']);

// Public posts routes
Route::get('/berita', [PublicPostController::class, 'index']);
Route::get('/berita/{post:slug}', [PublicPostController::class, 'show']);
Route::get('/kategori/{category:slug}', [PublicPostController::class, 'category']);

// Profile routes
Route::patch('/profile/password', [ProfileController::class, 'updatePassword']);
```

## Middleware dan Authorization

### Middleware Baru
- `SuperAdminMiddleware` - Hanya superadmin
- `AdminGerejaMiddleware` - Hanya admin gereja

### Policy
- `PostPolicy` - Authorization untuk CRUD posting
  - Superadmin: akses penuh
  - Admin gereja: hanya posting gerejanya
  - User lain: tidak ada akses

## View dan UI

### View Baru
- `posts/index.blade.php` - Daftar posting (admin)
- `posts/create.blade.php` - Form buat posting
- `posts/edit.blade.php` - Form edit posting
- `posts/show.blade.php` - Detail posting (admin)
- `categories/index.blade.php` - Daftar kategori
- `categories/create.blade.php` - Form buat kategori
- `categories/edit.blade.php` - Form edit kategori
- `categories/show.blade.php` - Detail kategori
- `admin/edit-church.blade.php` - Form edit gereja
- `admin/users.blade.php` - Daftar user (superadmin)
- `admin/reset-password.blade.php` - Form reset password
- `public/posts/index.blade.php` - Halaman berita publik
- `public/posts/show.blade.php` - Detail berita publik
- `public/posts/category.blade.php` - Berita per kategori

### Dashboard Update
- Dashboard diperbarui dengan quick actions
- Link ke semua fitur baru
- Conditional display berdasarkan role user

## Seeder dan Data Sample

### Seeder Baru
- `CategorySeeder` - Kategori default (Berita Gereja, Pengumuman, Renungan, Kegiatan, Artikel)
- `PostSeeder` - Sample posting untuk testing

### Data Sample
- 5 kategori default dengan warna berbeda
- 5 sample posting dengan konten yang relevan
- Featured posts untuk testing

## Fitur Keamanan

### Authorization
- Role-based access control
- Middleware untuk proteksi route
- Policy untuk proteksi resource
- Validasi input pada semua form

### File Upload
- Validasi tipe file (hanya gambar)
- Validasi ukuran file (max 2MB)
- Storage di `storage/app/public/`
- Auto-delete file lama saat update

## Cara Penggunaan

### Untuk Admin Gereja
1. Login dengan akun admin gereja
2. Akses Dashboard untuk melihat quick actions
3. **Kelola Posting**: `/posts` - Buat, edit, hapus posting
4. **Kelola Kategori**: `/categories` - Buat, edit, hapus kategori
5. **Edit Data Gereja**: `/admin/edit-church` - Update informasi gereja
6. **Edit Profil**: `/profile` - Update profil dan password

### Untuk Superadmin
1. Login dengan akun superadmin
2. Akses Dashboard untuk melihat semua fitur
3. **Kelola User**: `/admin/users` - Lihat daftar user dan reset password
4. **Kelola Posting**: `/posts` - Lihat semua posting dari semua gereja
5. **Kelola Kategori**: `/categories` - Kelola kategori global

### Untuk Pengunjung
1. Akses halaman utama `/`
2. Klik "Lihat Berita" untuk melihat semua berita
3. Filter berita berdasarkan kategori
4. Baca detail berita dengan related posts

## Teknologi yang Digunakan

- **Backend**: Laravel 11, PHP 8.2+
- **Database**: MySQL
- **Frontend**: Blade Templates, Tailwind CSS
- **File Storage**: Laravel Storage
- **Authorization**: Laravel Policies, Middleware
- **Validation**: Laravel Form Requests
- **UI Components**: Custom components dengan Tailwind CSS

## Catatan Penting

1. **File Upload**: Pastikan direktori `storage/app/public/posts` dan `storage/app/public/churches` memiliki permission yang tepat
2. **Storage Link**: Pastikan symlink storage sudah dibuat dengan `php artisan storage:link`
3. **Database**: Jalankan migration dan seeder untuk setup awal
4. **Permissions**: Pastikan user memiliki role yang tepat untuk mengakses fitur
5. **SEO**: URL menggunakan slug untuk SEO-friendly

## Troubleshooting

### Error Upload File
- Pastikan direktori storage ada dan writable
- Check permission folder `storage/app/public/`
- Pastikan symlink storage sudah dibuat

### Error Authorization
- Pastikan user memiliki role yang tepat
- Check middleware registration di `Kernel.php`
- Pastikan policy sudah didaftar di `AuthServiceProvider.php`

### Error Database
- Pastikan migration sudah dijalankan
- Check foreign key constraints
- Pastikan seeder sudah dijalankan untuk data sample
