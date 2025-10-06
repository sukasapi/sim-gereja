<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Database\Seeders\UserSeeder;

$seeder = new UserSeeder();
$seeder->run();

echo "Seeder berhasil dijalankan!\n"; 