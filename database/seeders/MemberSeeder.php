<?php

namespace Database\Seeders;

use App\Models\Church;
use App\Models\Member;
use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $churches = Church::all();

        foreach ($churches as $church) {
            $regions = Region::where('church_id', $church->id)->get();
            
            // Buat 20 member per gereja
            for ($i = 1; $i <= 20; $i++) {
                $member = Member::create([
                    'church_id' => $church->id,
                    'name' => 'Jemaat ' . $church->name . ' ' . $i,
                    'birth_date' => now()->subYears(rand(18, 70))->subDays(rand(1, 365)),
                    'gender' => rand(0, 1) ? 'L' : 'P',
                    'address' => 'Alamat Jemaat ' . $i . ', ' . $church->city,
                    'phone' => '08' . rand(100000000, 999999999),
                    'email' => 'jemaat' . $i . '@' . strtolower(str_replace(' ', '', $church->name)) . '.org',
                    'join_date' => now()->subYears(rand(1, 10))->subDays(rand(1, 365)),
                    'is_baptized' => rand(0, 1),
                    'is_sidi' => rand(0, 1),
                    'baptism_date' => rand(0, 1) ? now()->subYears(rand(1, 20))->subDays(rand(1, 365)) : null,
                    'sidi_date' => rand(0, 1) ? now()->subYears(rand(1, 15))->subDays(rand(1, 365)) : null,
                    'ministry_notes' => 'Catatan pelayanan untuk jemaat ' . $i,
                    'notes' => 'Catatan umum untuk jemaat ' . $i,
                    'is_active' => true,
                ]);
                
                // Assign member ke 1-3 region secara random
                $randomRegions = $regions->random(rand(1, 3));
                $member->regions()->attach($randomRegions->pluck('id'));
            }
        }
    }
}
