<?php

namespace Database\Seeders;

use App\Enums\AccountUsageType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountUsageTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (AccountUsageType::cases() as $row) {
            \App\Models\AccountUsageType::firstOrCreate([
                'name' => $row->value
            ], [
                'code' => $row->name,
                'name' => $row->value,
            ]);
        }
    }
}
