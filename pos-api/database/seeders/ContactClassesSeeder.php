<?php

namespace Database\Seeders;

use App\Enums\AccountUsageType;
use App\Models\ContactClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $contactClasses = [
            ['name' => 'Student'],
            ['name' => 'Employee'],
            ['name' => 'Others']
        ];
        foreach ($contactClasses as $row) {
            ContactClass::firstOrCreate([
                'name' => $row['name']
            ], [
                'name' => $row['name'],
            ]);
        }
    }
}
