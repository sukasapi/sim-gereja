<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder untuk multi-church
        $this->call([
            ChurchSeeder::class,
            RegionTypeSeeder::class,
            RegionSeeder::class,
            MemberSeeder::class,
        ]);

        // Buat user superadmin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_SUPERADMIN,
            'church_id' => null, // Superadmin tidak terikat gereja
        ]);

        // Buat user admin gereja untuk setiap gereja
        $churches = \App\Models\Church::all();
        foreach ($churches as $church) {
            User::create([
                'name' => 'Admin ' . $church->name,
                'email' => 'admin@' . strtolower(str_replace(' ', '', $church->name)) . '.org',
                'password' => bcrypt('password'),
                'role' => User::ROLE_ADMIN_GEREJA,
                'church_id' => $church->id,
            ]);
        }
    }
}
