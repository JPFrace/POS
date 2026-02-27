<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $regions = [
            ['name' => 'Ilocos Region (Region I)'],
            ['name' => 'Cagayan Valley (Region II)'],
            ['name' => 'Central Luzon (Region III)'],
            ['name' => 'CALABARZON (Region IV-A)'],
            ['name' => 'MIMAROPA (Region IV-B)'],
            ['name' => 'Bicol Region (Region V)'],
            ['name' => 'Western Visayas (Region VI)'],
            ['name' => 'Central Visayas (Region VII)'],
            ['name' => 'Eastern Visayas (Region VIII)'],
            ['name' => 'Zamboanga Peninsula (Region IX)'],
            ['name' => 'Northern Mindanao (Region X)'],
            ['name' => 'Davao Region (Region XI)'],
            ['name' => 'SOCCSKSARGEN (Region XII)'],
            ['name' => 'Caraga (Region XIII)'],
            ['name' => 'Bangsamoro Autonomous Region in Muslim Mindanao (BARMM)'],
            ['name' => 'Cordillera Administrative Region (CAR)'],
            ['name' => 'National Capital Region (NCR)']
        ];

        foreach ($regions as $region) {
            Region::firstOrCreate(
                ['name' => $region['name']]
            );
        }
    }
}
