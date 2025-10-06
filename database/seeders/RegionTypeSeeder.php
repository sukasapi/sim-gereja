<?php

namespace Database\Seeders;

use App\Models\Church;
use App\Models\RegionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $churches = Church::all();

        foreach ($churches as $church) {
            $config = $church->config;
            
            if (isset($config['region_types'])) {
                // Buat region types dalam urutan yang benar (parent dulu, baru child)
                $regionTypesData = $config['region_types'];
                $createdRegionTypes = [];
                
                // Buat dalam 3 tahap: level 1, level 2, level 3
                for ($level = 1; $level <= 3; $level++) {
                    $levelTypes = collect($regionTypesData)->where('level', $level);
                    
                    foreach ($levelTypes as $regionTypeData) {
                        $parentId = null;
                        
                        // Cari parent jika ada
                        if (isset($regionTypeData['parent']) && $regionTypeData['parent']) {
                            $parentSlug = $regionTypeData['parent'];
                            $parent = collect($createdRegionTypes)->firstWhere('slug', $parentSlug);
                            if ($parent) {
                                $parentId = $parent['id'];
                            }
                        }
                        
                        $regionType = RegionType::create([
                            'church_id' => $church->id,
                            'name' => $regionTypeData['name'],
                            'slug' => $regionTypeData['slug'],
                            'level' => $regionTypeData['level'],
                            'parent_id' => $parentId,
                            'sort_order' => $regionTypeData['sort_order'] ?? 0,
                            'description' => 'Tipe wilayah ' . $regionTypeData['name'] . ' untuk ' . $church->name,
                            'is_active' => true,
                        ]);
                        
                        $createdRegionTypes[] = [
                            'id' => $regionType->id,
                            'slug' => $regionType->slug,
                            'name' => $regionType->name,
                        ];
                    }
                }
            }
        }
    }
}
