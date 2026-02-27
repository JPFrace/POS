<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxonomySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Posted',
                'tags' => ['posting'],
                'description' => ''
            ],
            [
                'name' => 'Unposted',
                'tags' => ['posting'],
                'description' => ''
            ]
        ];

        foreach ($data as $row) {
            \App\Models\Taxonomy::firstOrCreate([
                'name' => $row['name'],
                'tags' => $row['tags'],
            ], [
                'description' => $row['description'],
            ]);
        }
    }
}
