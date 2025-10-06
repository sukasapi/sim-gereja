<?php

namespace Database\Seeders;

use App\Models\Church;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChurchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $churches = [
            [
                'name' => 'GKJ Prambanan',
                'address' => 'Jl. Raya Prambanan No. 1, Prambanan, Sleman, Yogyakarta',
                'city' => 'Sleman',
                'province' => 'Yogyakarta',
                'postal_code' => '55571',
                'phone' => '(0274) 496123',
                'email' => 'info@gkjprambanan.org',
                'website' => 'https://gkjprambanan.org',
                'is_active' => true,
                'config' => [
                    'region_types' => [
                        ['name' => 'Pepanthan', 'slug' => 'pepanthan', 'level' => 1, 'parent' => null, 'sort_order' => 1],
                        ['name' => 'Blok', 'slug' => 'blok', 'level' => 2, 'parent' => 'pepanthan', 'sort_order' => 1],
                        ['name' => 'Lingkungan', 'slug' => 'lingkungan', 'level' => 3, 'parent' => 'blok', 'sort_order' => 1],
                    ]
                ]
            ],
            [
                'name' => 'GKJ Gondokusuman',
                'address' => 'Jl. Gondokusuman No. 1, Gondokusuman, Yogyakarta',
                'city' => 'Yogyakarta',
                'province' => 'Yogyakarta',
                'postal_code' => '55122',
                'phone' => '(0274) 512345',
                'email' => 'info@gkjgondokusuman.org',
                'website' => 'https://gkjgondokusuman.org',
                'is_active' => true,
                'config' => [
                    'region_types' => [
                        ['name' => 'Sektor', 'slug' => 'sektor', 'level' => 1, 'parent' => null, 'sort_order' => 1],
                        ['name' => 'Lingkungan', 'slug' => 'lingkungan', 'level' => 2, 'parent' => 'sektor', 'sort_order' => 1],
                        ['name' => 'RT', 'slug' => 'rt', 'level' => 3, 'parent' => 'lingkungan', 'sort_order' => 1],
                    ]
                ]
            ],
            [
                'name' => 'GKJ Wirobrajan',
                'address' => 'Jl. Wirobrajan No. 1, Wirobrajan, Yogyakarta',
                'city' => 'Yogyakarta',
                'province' => 'Yogyakarta',
                'postal_code' => '55152',
                'phone' => '(0274) 523456',
                'email' => 'info@gkjwirobrajan.org',
                'website' => 'https://gkjwirobrajan.org',
                'is_active' => true,
                'config' => [
                    'region_types' => [
                        ['name' => 'Wilayah', 'slug' => 'wilayah', 'level' => 1, 'parent' => null, 'sort_order' => 1],
                        ['name' => 'Kelompok', 'slug' => 'kelompok', 'level' => 2, 'parent' => 'wilayah', 'sort_order' => 1],
                        ['name' => 'Sub Kelompok', 'slug' => 'sub-kelompok', 'level' => 3, 'parent' => 'kelompok', 'sort_order' => 1],
                    ]
                ]
            ],
        ];

        foreach ($churches as $churchData) {
            Church::create($churchData);
        }
    }
}
