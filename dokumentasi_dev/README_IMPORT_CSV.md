# Fitur Import Data Jemaat CSV

## 🎯 Overview
Fitur import CSV memungkinkan admin untuk mengimpor data jemaat secara massal dari file CSV. Fitur ini mendukung relasi antara jemaat, wilayah, dan silsilah keluarga.

## 📋 Format CSV yang Didukung

### Header CSV
```csv
nama,tanggal_lahir,jenis_kelamin,alamat,telepon,email,wilayah,ayah,ibu,tanggal_gabung,status_baptis,status_sidi
```

### Penjelasan Kolom
- **nama**: Nama lengkap jemaat (wajib)
- **tanggal_lahir**: Format dd/mm/yyyy (contoh: 15/03/1990)
- **jenis_kelamin**: L untuk Laki-laki, P untuk Perempuan
- **alamat**: Alamat lengkap jemaat
- **telepon**: Nomor telepon
- **email**: Alamat email
- **wilayah**: Nama wilayah (akan dicocokkan dengan data wilayah yang ada)
- **ayah**: Nama ayah (akan dicocokkan dengan jemaat laki-laki yang ada)
- **ibu**: Nama ibu (akan dicocokkan dengan jemaat perempuan yang ada)
- **tanggal_gabung**: Format dd/mm/yyyy (contoh: 01/01/2020)
- **status_baptis**: Ya/1 untuk sudah dibaptis, Tidak/0 untuk belum
- **status_sidi**: Ya/1 untuk sudah sidi, Tidak/0 untuk belum

## 📁 Contoh File CSV

### File: `contoh_import_jemaat.csv`
```csv
nama,tanggal_lahir,jenis_kelamin,alamat,telepon,email,wilayah,ayah,ibu,tanggal_gabung,status_baptis,status_sidi
John Doe,15/03/1990,L,Jl. Merdeka No. 123,081234567890,john@example.com,Blok A,Budi Doe,Siti Doe,01/01/2020,Ya,Ya
Jane Smith,20/07/1995,P,Jl. Sudirman No. 456,081234567891,jane@example.com,Blok B,John Smith,Mary Smith,01/01/2020,Ya,Tidak
Bob Johnson,10/12/1985,L,Jl. Gatot Subroto No. 789,081234567892,bob@example.com,Blok C,Robert Johnson,Linda Johnson,01/01/2020,Tidak,Tidak
Alice Brown,25/09/1992,P,Jl. Thamrin No. 321,081234567893,alice@example.com,Blok A,David Brown,Sarah Brown,01/01/2020,Ya,Ya
Charlie Wilson,05/11/1988,L,Jl. Kebon Jeruk No. 654,081234567894,charlie@example.com,Blok B,Michael Wilson,Emma Wilson,01/01/2020,Ya,Tidak
```

## 🚀 Cara Menggunakan

### 1. Akses Fitur Import
1. Login ke admin panel
2. Buka menu "Data Jemaat"
3. Klik tombol "Import CSV" di header tabel

### 2. Upload File CSV
1. Pilih file CSV yang akan diimpor
2. Pilih gereja tujuan (untuk superadmin)
3. Tentukan apakah akan skip duplikat
4. Klik "Import"

### 3. Proses Import
- Sistem akan memvalidasi setiap baris
- Mencocokkan wilayah dengan data yang ada
- Mencocokkan ayah/ibu dengan jemaat yang ada
- Membuat data jemaat baru
- Menampilkan notifikasi hasil import

## 🔍 Fitur Import

### 1. Validasi Data
- ✅ Format tanggal yang benar (dd/mm/yyyy)
- ✅ Jenis kelamin yang valid (L/P)
- ✅ Status baptis/sidi yang valid (Ya/Tidak atau 1/0)
- ✅ Email format yang valid

### 2. Pencocokan Relasi
- **Wilayah**: Mencocokkan nama wilayah dengan data yang ada
- **Ayah**: Mencocokkan nama ayah dengan jemaat laki-laki yang ada
- **Ibu**: Mencocokkan nama ibu dengan jemaat perempuan yang ada

### 3. Skip Duplikat
- Opsi untuk melewati data yang sudah ada
- Duplikat ditentukan berdasarkan nama dan tanggal lahir
- Mencegah data ganda dalam sistem

### 4. Error Handling
- Menampilkan error untuk baris yang bermasalah
- Melanjutkan import untuk baris yang valid
- Notifikasi lengkap hasil import

## 📊 Hasil Import

### Notifikasi Hasil
```
Import CSV Berhasil
Import selesai! Berhasil: 5, Dilewati: 0, Error: 0
```

### Statistik Import
- **Berhasil**: Jumlah data yang berhasil diimpor
- **Dilewati**: Jumlah data yang dilewati (duplikat)
- **Error**: Jumlah baris yang error

## 🛠️ Konfigurasi

### 1. File Upload
- **Directory**: `storage/app/imports/`
- **File Types**: CSV files only
- **Auto Delete**: File dihapus setelah import selesai

### 2. Pencocokan Data
- **Wilayah**: Pencocokan partial (LIKE %nama%)
- **Ayah/Ibu**: Pencocokan partial dengan filter gender
- **Case Insensitive**: Tidak case sensitive

### 3. Default Values
- **is_active**: true (semua jemaat aktif)
- **church_id**: Gereja yang dipilih
- **region_id**: Wilayah yang dicocokkan
- **father_id/mother_id**: Orang tua yang dicocokkan

## 🔧 Troubleshooting

### Error Umum

#### 1. File CSV tidak ditemukan
```
Error: File CSV tidak ditemukan.
```
**Solusi**: Pastikan file CSV sudah diupload dengan benar

#### 2. Format tanggal salah
```
Error: Baris 2: Invalid date format
```
**Solusi**: Gunakan format dd/mm/yyyy (contoh: 15/03/1990)

#### 3. Wilayah tidak ditemukan
```
Warning: Wilayah "Blok X" tidak ditemukan untuk jemaat "John Doe"
```
**Solusi**: Pastikan nama wilayah sesuai dengan data yang ada

#### 4. Ayah/Ibu tidak ditemukan
```
Warning: Ayah "Budi Doe" tidak ditemukan untuk jemaat "John Doe"
```
**Solusi**: Pastikan ayah/ibu sudah terdaftar sebagai jemaat

### Tips Import

#### 1. Persiapan Data
- Pastikan data wilayah sudah ada
- Pastikan data ayah/ibu sudah ada (jika ada)
- Gunakan format tanggal yang konsisten
- Validasi data sebelum import

#### 2. Import Bertahap
- Import data ayah/ibu terlebih dahulu
- Import data wilayah terlebih dahulu
- Import data jemaat secara bertahap

#### 3. Backup Data
- Backup database sebelum import massal
- Test import dengan data kecil terlebih dahulu

## 📈 Best Practices

### 1. Struktur Data
- Gunakan header yang sesuai
- Pastikan format data konsisten
- Validasi data sebelum import

### 2. Import Strategy
- Import data master terlebih dahulu (wilayah, orang tua)
- Import data jemaat secara bertahap
- Monitor hasil import

### 3. Data Quality
- Pastikan data akurat
- Gunakan format standar
- Validasi relasi data

## ✅ Summary

**Fitur Import CSV telah berhasil diimplementasikan!**

### Features Implemented:
1. ✅ **Form Reorganization**: Wilayah dipindah ke bawah informasi pribadi
2. ✅ **Single Select Region**: Wilayah diubah dari multiple ke single select
3. ✅ **Parent Relationships**: Relasi ayah/ibu ditambahkan
4. ✅ **CSV Import**: Fitur import CSV dengan validasi lengkap
5. ✅ **Relationship Matching**: Pencocokan wilayah dan silsilah
6. ✅ **Error Handling**: Handling error dan notifikasi
7. ✅ **Duplicate Prevention**: Skip duplikat berdasarkan nama dan tanggal lahir

### Database Changes:
- ✅ Added `father_id`, `mother_id`, `region_id` to members table
- ✅ Added relationships in Member model
- ✅ Updated MemberResource form and table

### Import Features:
- ✅ CSV file upload
- ✅ Data validation
- ✅ Relationship matching
- ✅ Duplicate detection
- ✅ Error reporting
- ✅ Success notification

**Aplikasi Multi-Church Architecture sekarang mendukung import data jemaat secara massal dengan relasi lengkap!** 🚀
