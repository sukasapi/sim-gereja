# Template Import Jemaat - Panduan Lengkap

## 🎯 Overview
Dokumentasi ini menjelaskan cara menggunakan template CSV untuk import data jemaat dengan benar.

## 📋 Template CSV

### Format Template
Template CSV berisi kolom-kolom berikut:

```csv
nama,tanggal_lahir,jenis_kelamin,alamat,telepon,email,wilayah,ayah,ibu,tanggal_gabung,status_baptis,status_sidi,catatan_pelayanan,catatan_umum
```

### Contoh Data Template
```csv
nama,tanggal_lahir,jenis_kelamin,alamat,telepon,email,wilayah,ayah,ibu,tanggal_gabung,status_baptis,status_sidi,catatan_pelayanan,catatan_umum
John Doe,15/01/1990,L,Jl. Merdeka No. 123,081234567890,john@example.com,Blok A,Jane Doe,Robert Doe,01/01/2020,Ya,Tidak,Pelayanan Musik,Anggota aktif
Jane Smith,20/05/1985,P,Jl. Sudirman No. 456,081234567891,jane@example.com,Blok B,John Smith,Mary Smith,01/01/2019,Tidak,Ya,Pelayanan Anak,Anggota baru
Robert Johnson,10/12/1992,L,Jl. Gatot Subroto No. 789,081234567892,robert@example.com,Blok C,Michael Johnson,Sarah Johnson,01/01/2021,Ya,Ya,Pelayanan Remaja,Anggota muda
```

## 📝 Penjelasan Kolom

### 1. **nama** (Wajib)
- **Format**: Text
- **Contoh**: "John Doe"
- **Keterangan**: Nama lengkap jemaat

### 2. **tanggal_lahir** (Opsional)
- **Format**: DD/MM/YYYY
- **Contoh**: "15/01/1990"
- **Keterangan**: Tanggal lahir dalam format Indonesia

### 3. **jenis_kelamin** (Wajib)
- **Format**: L atau P
- **Contoh**: "L" (Laki-laki) atau "P" (Perempuan)
- **Keterangan**: Jenis kelamin jemaat

### 4. **alamat** (Opsional)
- **Format**: Text
- **Contoh**: "Jl. Merdeka No. 123"
- **Keterangan**: Alamat lengkap jemaat

### 5. **telepon** (Opsional)
- **Format**: Text
- **Contoh**: "081234567890"
- **Keterangan**: Nomor telepon jemaat

### 6. **email** (Opsional)
- **Format**: Email
- **Contoh**: "john@example.com"
- **Keterangan**: Alamat email jemaat

### 7. **wilayah** (Opsional)
- **Format**: Text
- **Contoh**: "Blok A"
- **Keterangan**: Nama wilayah sesuai dengan data di database

### 8. **ayah** (Opsional)
- **Format**: Text
- **Contoh**: "Robert Doe"
- **Keterangan**: Nama ayah (harus sudah terdaftar sebagai jemaat)

### 9. **ibu** (Opsional)
- **Format**: Text
- **Contoh**: "Jane Doe"
- **Keterangan**: Nama ibu (harus sudah terdaftar sebagai jemaat)

### 10. **tanggal_gabung** (Opsional)
- **Format**: DD/MM/YYYY
- **Contoh**: "01/01/2020"
- **Keterangan**: Tanggal bergabung dengan gereja

### 11. **status_baptis** (Opsional)
- **Format**: Ya atau Tidak
- **Contoh**: "Ya" atau "Tidak"
- **Keterangan**: Status baptis jemaat

### 12. **status_sidi** (Opsional)
- **Format**: Ya atau Tidak
- **Contoh**: "Ya" atau "Tidak"
- **Keterangan**: Status Sidi jemaat

### 13. **catatan_pelayanan** (Opsional)
- **Format**: Text
- **Contoh**: "Pelayanan Musik"
- **Keterangan**: Catatan pelayanan jemaat

### 14. **catatan_umum** (Opsional)
- **Format**: Text
- **Contoh**: "Anggota aktif"
- **Keterangan**: Catatan umum tentang jemaat

## 🗺️ Daftar Wilayah Tersedia

### Cara Melihat Daftar Wilayah
1. Buka halaman **Import Jemaat**
2. Klik pada section **"Daftar Wilayah Tersedia"**
3. Lihat daftar wilayah yang tersedia di database

### Format Wilayah
- **Nama Wilayah**: Nama wilayah yang terdaftar
- **Tipe Wilayah**: Jenis wilayah (Blok, Pepanthan, Sektor, dll)
- **Gereja**: Nama gereja (untuk superadmin)

### Contoh Daftar Wilayah
```
Blok A (Tipe: Blok)
Blok B (Tipe: Blok)
Pepanthan 1 (Tipe: Pepanthan)
Pepanthan 2 (Tipe: Pepanthan)
Sektor A (Tipe: Sektor)
```

## 📋 Langkah-langkah Import

### 1. **Download Template**
- Klik tombol **"Download Template CSV"**
- Simpan file template ke komputer
- Buka file dengan Excel atau aplikasi spreadsheet

### 2. **Isi Data**
- Isi data jemaat sesuai dengan format template
- Pastikan format tanggal menggunakan DD/MM/YYYY
- Pastikan jenis kelamin menggunakan L atau P
- Pastikan status baptis/sidi menggunakan Ya atau Tidak

### 3. **Periksa Wilayah**
- Pastikan nama wilayah sesuai dengan yang ada di database
- Lihat daftar wilayah tersedia di form import
- Jika wilayah belum ada, tambahkan terlebih dahulu

### 4. **Upload File**
- Simpan file CSV
- Upload file melalui form import
- Pilih gereja (untuk superadmin)
- Pilih opsi skip duplikat jika diperlukan

### 5. **Proses Import**
- Klik tombol **"Create"** untuk memulai import
- Tunggu proses import selesai
- Lihat hasil import di tabel

## ⚠️ Catatan Penting

### Format Data
- **Tanggal**: Gunakan format DD/MM/YYYY
- **Jenis Kelamin**: Gunakan L (Laki-laki) atau P (Perempuan)
- **Status**: Gunakan Ya atau Tidak
- **Wilayah**: Pastikan sesuai dengan data di database

### Validasi Data
- **Nama**: Wajib diisi
- **Jenis Kelamin**: Wajib diisi
- **Wilayah**: Harus sesuai dengan data di database
- **Ayah/Ibu**: Harus sudah terdaftar sebagai jemaat

### Skip Duplikat
- Jika diaktifkan, data dengan nama dan tanggal lahir yang sama akan dilewati
- Jika tidak diaktifkan, akan terjadi error jika ada duplikat

## 🔧 Troubleshooting

### Error: Wilayah tidak ditemukan
- **Penyebab**: Nama wilayah tidak sesuai dengan data di database
- **Solusi**: Periksa daftar wilayah tersedia dan sesuaikan nama

### Error: Ayah/Ibu tidak ditemukan
- **Penyebab**: Nama ayah/ibu tidak terdaftar sebagai jemaat
- **Solusi**: Pastikan ayah/ibu sudah terdaftar terlebih dahulu

### Error: Format tanggal salah
- **Penyebab**: Format tanggal tidak sesuai DD/MM/YYYY
- **Solusi**: Ubah format tanggal menjadi DD/MM/YYYY

### Error: Format jenis kelamin salah
- **Penyebab**: Jenis kelamin bukan L atau P
- **Solusi**: Ubah menjadi L (Laki-laki) atau P (Perempuan)

## 📊 Hasil Import

### Status Import
- **Pending**: Menunggu diproses
- **Processing**: Sedang diproses
- **Completed**: Berhasil diproses
- **Failed**: Gagal diproses

### Laporan Import
- **Total Baris**: Jumlah total baris data
- **Berhasil**: Jumlah data yang berhasil diimpor
- **Dilewati**: Jumlah data yang dilewati (duplikat)
- **Error**: Jumlah data yang error

## 🎉 Tips Sukses Import

### 1. **Persiapan Data**
- Pastikan data sudah lengkap dan benar
- Periksa format tanggal dan jenis kelamin
- Pastikan wilayah sudah ada di database

### 2. **Validasi Sebelum Import**
- Periksa daftar wilayah tersedia
- Pastikan ayah/ibu sudah terdaftar
- Periksa format data sesuai template

### 3. **Proses Import**
- Gunakan template yang disediakan
- Aktifkan skip duplikat jika diperlukan
- Tunggu proses import selesai

### 4. **Verifikasi Hasil**
- Periksa laporan import
- Lihat data yang berhasil diimpor
- Perbaiki data yang error jika ada

**Dengan mengikuti panduan ini, proses import data jemaat akan berjalan dengan lancar dan akurat!** 🚀
