<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin'.rand(1000,9999).'@gkjprambanan.org',
            'password' => Hash::make('123456'),
            'role' => User::ROLE_SUPERADMIN,
        ]);
        User::create([
            'name' => 'Admin Gereja',
            'email' => 'admingereja'.rand(1000,9999).'@gkjprambanan.org',
            'password' => Hash::make('123456'),
            'role' => User::ROLE_ADMIN_GEREJA,
        ]);
        User::create([
            'name' => 'Admin Komisi',
            'email' => 'adminkomisi'.rand(1000,9999).'@gkjprambanan.org',
            'password' => Hash::make('123456'),
            'role' => User::ROLE_ADMIN_KOMISI,
        ]);
        User::create([
            'name' => 'Jemaat',
            'email' => 'jemaat'.rand(1000,9999).'@gkjprambanan.org',
            'password' => Hash::make('123456'),
            'role' => User::ROLE_JEMAAT,
        ]);
    }
} 