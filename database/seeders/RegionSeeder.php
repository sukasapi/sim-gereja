<?php

namespace Database\Seeders;

use App\Models\Church;
use App\Models\Region;
use App\Models\RegionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $churches = Church::all();

        foreach ($churches as $church) {
            $regionTypes = RegionType::where('church_id', $church->id)->get();
            
            // Buat region level 1 (parent)
            $level1Regions = [];
            foreach ($regionTypes->where('level', 1) as $regionType) {
                $regions = [
                    ['name' => $regionType->name . ' A', 'slug' => $regionType->slug . '-a'],
                    ['name' => $regionType->name . ' B', 'slug' => $regionType->slug . '-b'],
                    ['name' => $regionType->name . ' C', 'slug' => $regionType->slug . '-c'],
                ];
                
                foreach ($regions as $regionData) {
                    $region = Region::create([
                        'church_id' => $church->id,
                        'region_type_id' => $regionType->id,
                        'name' => $regionData['name'],
                        'slug' => $regionData['slug'],
                        'description' => 'Wilayah ' . $regionData['name'] . ' di ' . $church->name,
                        'is_active' => true,
                    ]);
                    $level1Regions[] = $region;
                }
            }
            
            // Buat region level 2 (child)
            foreach ($regionTypes->where('level', 2) as $regionType) {
                foreach ($level1Regions as $index => $parentRegion) {
                    $regions = [
                        ['name' => $regionType->name . ' 1', 'slug' => $regionType->slug . '-' . $parentRegion->id . '-1'],
                        ['name' => $regionType->name . ' 2', 'slug' => $regionType->slug . '-' . $parentRegion->id . '-2'],
                    ];
                    
                    foreach ($regions as $regionData) {
                        Region::create([
                            'church_id' => $church->id,
                            'region_type_id' => $regionType->id,
                            'name' => $regionData['name'],
                            'slug' => $regionData['slug'],
                            'description' => 'Wilayah ' . $regionData['name'] . ' di ' . $parentRegion->name,
                            'parent_id' => $parentRegion->id,
                            'is_active' => true,
                        ]);
                    }
                }
            }
        }
    }
}
