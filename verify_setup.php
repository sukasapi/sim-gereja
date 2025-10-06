<?php

/**
 * Script untuk verifikasi setup Multi-Church Architecture
 * Jalankan dengan: php verify_setup.php
 */

echo "=== Verifikasi Setup Multi-Church Architecture ===\n\n";

// Check if we're in Laravel environment
if (!file_exists('artisan')) {
    echo "❌ Error: Script harus dijalankan di root directory Laravel\n";
    exit(1);
}

echo "1. Memeriksa database connection...\n";
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=gkjprambanan_db', 'root', '');
    echo "✅ Database connection berhasil\n\n";
} catch (PDOException $e) {
    echo "❌ Database connection gagal: " . $e->getMessage() . "\n";
    exit(1);
}

echo "2. Memeriksa tabel database...\n";
$requiredTables = [
    'churches',
    'region_types', 
    'regions',
    'members',
    'member_region',
    'users'
];

$missingTables = [];
foreach ($requiredTables as $table) {
    $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
    $stmt->execute([$table]);
    if ($stmt->rowCount() == 0) {
        $missingTables[] = $table;
    }
}

if (empty($missingTables)) {
    echo "✅ Semua tabel database tersedia\n\n";
} else {
    echo "❌ Tabel yang hilang: " . implode(', ', $missingTables) . "\n";
    exit(1);
}

echo "3. Memeriksa data gereja...\n";
$stmt = $pdo->query("SELECT COUNT(*) as count FROM churches");
$churchCount = $stmt->fetch()['count'];

if ($churchCount >= 3) {
    echo "✅ Data gereja tersedia ($churchCount gereja)\n\n";
} else {
    echo "❌ Data gereja tidak lengkap (hanya $churchCount gereja)\n";
    exit(1);
}

echo "4. Memeriksa data jemaat...\n";
$stmt = $pdo->query("SELECT COUNT(*) as count FROM members");
$memberCount = $stmt->fetch()['count'];

if ($memberCount >= 60) {
    echo "✅ Data jemaat tersedia ($memberCount jemaat)\n\n";
} else {
    echo "❌ Data jemaat tidak lengkap (hanya $memberCount jemaat)\n";
    exit(1);
}

echo "5. Memeriksa user admin...\n";
$stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role IN ('superadmin', 'admin_gereja')");
$adminCount = $stmt->fetch()['count'];

if ($adminCount >= 4) {
    echo "✅ User admin tersedia ($adminCount admin)\n\n";
} else {
    echo "❌ User admin tidak lengkap (hanya $adminCount admin)\n";
    exit(1);
}

echo "6. Memeriksa file storage link...\n";
if (is_dir('public/storage') || is_link('public/storage')) {
    echo "✅ Storage link tersedia\n\n";
} else {
    echo "❌ Storage link tidak tersedia\n";
    echo "Jalankan: php artisan storage:link\n";
    exit(1);
}

echo "7. Memeriksa Filament Resources...\n";
$filamentResources = [
    'app/Filament/Admin/Resources/ChurchResource.php',
    'app/Filament/Admin/Resources/RegionTypeResource.php',
    'app/Filament/Admin/Resources/RegionResource.php',
    'app/Filament/Admin/Resources/MemberResource.php'
];

$missingResources = [];
foreach ($filamentResources as $resource) {
    if (!file_exists($resource)) {
        $missingResources[] = $resource;
    }
}

if (empty($missingResources)) {
    echo "✅ Semua Filament Resources tersedia\n\n";
} else {
    echo "❌ Filament Resources yang hilang: " . implode(', ', $missingResources) . "\n";
    exit(1);
}

echo "8. Memeriksa middleware...\n";
if (file_exists('app/Http/Middleware/MultiChurchMiddleware.php')) {
    echo "✅ MultiChurchMiddleware tersedia\n\n";
} else {
    echo "❌ MultiChurchMiddleware tidak tersedia\n";
    exit(1);
}

echo "9. Memeriksa helper functions...\n";
if (file_exists('app/Helpers/ChurchHelper.php')) {
    echo "✅ ChurchHelper tersedia\n\n";
} else {
    echo "❌ ChurchHelper tidak tersedia\n";
    exit(1);
}

echo "10. Memeriksa public views...\n";
$publicViews = [
    'resources/views/churches/index.blade.php',
    'resources/views/churches/show.blade.php'
];

$missingViews = [];
foreach ($publicViews as $view) {
    if (!file_exists($view)) {
        $missingViews[] = $view;
    }
}

if (empty($missingViews)) {
    echo "✅ Semua public views tersedia\n\n";
} else {
    echo "❌ Public views yang hilang: " . implode(', ', $missingViews) . "\n";
    exit(1);
}

echo "=== Verifikasi Selesai ===\n\n";

echo "🎉 Aplikasi Multi-Church Architecture siap digunakan!\n\n";

echo "📋 Informasi Login:\n";
echo "Superadmin: superadmin@example.com / password\n";
echo "Admin GKJ Prambanan: admin@gkjprambanan.org / password\n";
echo "Admin GKJ Gondokusuman: admin@gkjgondokusuman.org / password\n";
echo "Admin GKJ Wirobrajan: admin@gkjwirobrajan.org / password\n\n";

echo "📊 Data yang Tersedia:\n";
echo "- $churchCount Gereja\n";
echo "- $memberCount Jemaat\n";
echo "- $adminCount Admin\n\n";

echo "🌐 Akses Aplikasi:\n";
echo "Public: http://127.0.0.1:8000\n";
echo "Admin Panel: http://127.0.0.1:8000/admin\n\n";

echo "📖 Dokumentasi:\n";
echo "- Setup Guide: MULTI_CHURCH_SETUP.md\n";
echo "- README: README_MULTI_CHURCH.md\n\n";

echo "✅ Semua sistem berfungsi dengan baik!\n";
