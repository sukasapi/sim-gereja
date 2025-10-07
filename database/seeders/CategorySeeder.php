<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Berita Gereja',
                'slug' => 'berita-gereja',
                'description' => 'Berita dan informasi terkini dari gereja',
                'color' => '#3B82F6',
                'is_active' => true,
            ],
            [
                'name' => 'Pengumuman',
                'slug' => 'pengumuman',
                'description' => 'Pengumuman penting untuk jemaat',
                'color' => '#10B981',
                'is_active' => true,
            ],
            [
                'name' => 'Renungan',
                'slug' => 'renungan',
                'description' => 'Renungan harian dan spiritual',
                'color' => '#F59E0B',
                'is_active' => true,
            ],
            [
                'name' => 'Kegiatan',
                'slug' => 'kegiatan',
                'description' => 'Informasi kegiatan dan acara gereja',
                'color' => '#EF4444',
                'is_active' => true,
            ],
            [
                'name' => 'Artikel',
                'slug' => 'artikel',
                'description' => 'Artikel dan tulisan inspiratif',
                'color' => '#8B5CF6',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
