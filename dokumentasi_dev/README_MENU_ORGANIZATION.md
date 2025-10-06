# Menu Organization - Filament Admin Panel

## 🎯 Overview
Dokumentasi ini menjelaskan struktur menu yang telah diatur di Filament Admin Panel untuk aplikasi Multi-Church Architecture.

## 📋 Struktur Menu

### 1. 🏛️ Gereja
- **Resource**: ChurchResource
- **Navigation Sort**: 1
- **Icon**: heroicon-o-building-office-2
- **Label**: Gereja
- **Description**: Manajemen data gereja

### 2. 🗺️ Wilayah (Navigation Group)
Menu utama "Wilayah" dengan sub menu:

#### 2.1 Wilayah
- **Resource**: RegionResource
- **Navigation Sort**: 1
- **Icon**: heroicon-o-map
- **Label**: Wilayah
- **Description**: Manajemen wilayah gereja

#### 2.2 Tipe Wilayah
- **Resource**: RegionTypeResource
- **Navigation Sort**: 2
- **Icon**: heroicon-o-tag
- **Label**: Tipe Wilayah
- **Description**: Manajemen tipe wilayah (hierarchical)

### 3. 👥 Data Jemaat (Navigation Group)
Menu utama "Data Jemaat" dengan sub menu:

#### 3.1 Data Jemaat
- **Resource**: MemberResource
- **Navigation Sort**: 1
- **Icon**: heroicon-o-users
- **Label**: Data Jemaat
- **Description**: Manajemen data jemaat

#### 3.2 Laporan Jemaat
- **Resource**: MemberReportResource
- **Navigation Sort**: 2
- **Icon**: heroicon-o-document-chart-bar
- **Label**: Laporan Jemaat
- **Description**: Laporan data jemaat

#### 3.3 Statistik Jemaat
- **Resource**: MemberStatisticResource
- **Navigation Sort**: 3
- **Icon**: heroicon-o-chart-bar
- **Label**: Statistik Jemaat
- **Description**: Statistik dan analisis jemaat

#### 3.4 Export Jemaat
- **Resource**: MemberExportResource
- **Navigation Sort**: 4
- **Icon**: heroicon-o-arrow-down-tray
- **Label**: Export Jemaat
- **Description**: Export data jemaat

## 🔧 Konfigurasi

### Navigation Group
```php
protected static ?string $navigationGroup = 'Wilayah';
protected static ?string $navigationGroup = 'Data Jemaat';
```

### Navigation Sort
```php
protected static ?int $navigationSort = 1; // Urutan dalam group
```

### Navigation Label
```php
protected static ?string $navigationLabel = 'Tipe Wilayah';
```

### Model Label
```php
protected static ?string $modelLabel = 'Jemaat';
protected static ?string $pluralModelLabel = 'Data Jemaat';
```

## 🎨 Icons yang Digunakan

- **Gereja**: `heroicon-o-building-office-2`
- **Wilayah**: `heroicon-o-map`
- **Tipe Wilayah**: `heroicon-o-tag`
- **Data Jemaat**: `heroicon-o-users`
- **Laporan Jemaat**: `heroicon-o-document-chart-bar`
- **Statistik Jemaat**: `heroicon-o-chart-bar`
- **Export Jemaat**: `heroicon-o-arrow-down-tray`

## 🌐 Cara Mengakses

1. **Buka Admin Panel**: http://127.0.0.1:8000/admin
2. **Login** dengan kredensial:
   - Superadmin: superadmin@example.com / password
   - Church Admin: admin@gkjprambanan.org / password
3. **Lihat struktur menu** di sidebar kiri

## 📱 Tampilan Menu

```
📋 Admin Panel Menu
├── 🏛️ Gereja
├── 🗺️ Wilayah
│   ├── 🗺️ Wilayah
│   └── 🏷️ Tipe Wilayah
└── 👥 Data Jemaat
    ├── 👥 Data Jemaat
    ├── 📊 Laporan Jemaat
    ├── 📈 Statistik Jemaat
    └── ⬇️ Export Jemaat
```

## ✅ Benefits

1. **Organized Structure**: Menu terorganisir dengan baik berdasarkan fungsi
2. **User Friendly**: Mudah navigasi untuk admin
3. **Scalable**: Mudah menambah menu baru
4. **Consistent**: Konsistensi dalam penamaan dan icon
5. **Intuitive**: Struktur yang intuitif dan mudah dipahami

## 🔄 Future Enhancements

1. **Custom Icons**: Menggunakan custom icons untuk setiap menu
2. **Menu Permissions**: Menu berdasarkan role user
3. **Menu Badges**: Badge untuk notifikasi atau count
4. **Menu Search**: Pencarian menu
5. **Menu Favorites**: Menu favorit untuk quick access

## 📚 Related Documentation

- [Multi-Church Architecture](README_MULTI_CHURCH.md)
- [Hierarchical Region Types](README_HIERARCHICAL_REGION_TYPES.md)
- [Setup Guide](MULTI_CHURCH_SETUP.md)

## ✅ Status

**Menu Organization telah selesai dan siap digunakan!**

Struktur menu telah diatur sesuai permintaan dengan grouping yang jelas dan navigasi yang mudah.
