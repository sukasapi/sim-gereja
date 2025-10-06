<?php

/**
 * Script untuk setup Multi-Church Architecture
 * Jalankan dengan: php setup_multi_church.php
 */

echo "=== Multi-Church Architecture Setup ===\n\n";

// Check if we're in Laravel environment
if (!file_exists('artisan')) {
    echo "❌ Error: Script harus dijalankan di root directory Laravel\n";
    exit(1);
}

echo "1. Menjalankan migration...\n";
$migrationResult = shell_exec('php artisan migrate --force 2>&1');
echo $migrationResult;

if (strpos($migrationResult, 'Migrated:') !== false || strpos($migrationResult, 'Nothing to migrate') !== false) {
    echo "✅ Migration berhasil\n\n";
} else {
    echo "❌ Migration gagal\n";
    echo "Error: " . $migrationResult . "\n";
    exit(1);
}

echo "2. Menjalankan seeder...\n";
$seederResult = shell_exec('php artisan db:seed --force 2>&1');
echo $seederResult;

if (strpos($seederResult, 'Database seeding completed successfully') !== false) {
    echo "✅ Seeder berhasil\n\n";
} else {
    echo "❌ Seeder gagal\n";
    echo "Error: " . $seederResult . "\n";
    exit(1);
}

echo "3. Membuat storage link...\n";
$linkResult = shell_exec('php artisan storage:link 2>&1');
echo $linkResult;

echo "4. Clearing cache...\n";
shell_exec('php artisan config:clear');
shell_exec('php artisan cache:clear');
shell_exec('php artisan route:clear');
shell_exec('php artisan view:clear');

echo "\n=== Setup Selesai ===\n\n";

echo "📋 Informasi Login:\n";
echo "Superadmin: superadmin@example.com / password\n";
echo "Admin GKJ Prambanan: admin@gkjprambanan.org / password\n";
echo "Admin GKJ Gondokusuman: admin@gkjgondokusuman.org / password\n";
echo "Admin GKJ Wirobrajan: admin@gkjwirobrajan.org / password\n\n";

echo "📊 Data yang Dibuat:\n";
echo "- 3 Gereja dengan konfigurasi wilayah yang berbeda\n";
echo "- Tipe wilayah untuk setiap gereja\n";
echo "- Wilayah dengan hierarki\n";
echo "- 20 jemaat per gereja\n";
echo "- User admin untuk setiap gereja\n\n";

echo "🚀 Aplikasi siap digunakan!\n";
echo "Akses admin panel di: /admin\n\n";

echo "📖 Dokumentasi lengkap: MULTI_CHURCH_SETUP.md\n";

