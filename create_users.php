<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Hapus user yang sudah ada (opsional)
User::truncate();

// Buat user baru
$users = [
    [
        'name' => 'Super Admin',
        'email' => 'superadmin'.rand(1000,9999).'@example.com',
        'password' => Hash::make('123456'),
        'role' => 'superadmin',
    ],
    [
        'name' => 'Admin Gereja',
        'email' => 'admingereja'.rand(1000,9999).'@example.com',
        'password' => Hash::make('123456'),
        'role' => 'admin_gereja',
    ],
    [
        'name' => 'Admin Komisi',
        'email' => 'adminkomisi'.rand(1000,9999).'@example.com',
        'password' => Hash::make('123456'),
        'role' => 'admin_komisi',
    ],
    [
        'name' => 'Jemaat',
        'email' => 'jemaat'.rand(1000,9999).'@example.com',
        'password' => Hash::make('123456'),
        'role' => 'jemaat',
    ],
];

foreach ($users as $user) {
    User::create($user);
    echo "User {$user['name']} berhasil dibuat dengan email: {$user['email']}\n";
}

echo "\nSemua user berhasil dibuat!\n"; 