<?php

namespace Database\Seeders;

use App\Enums\ContactSubTypes;
use App\Models\ContactSubType;
use Illuminate\Database\Seeder;

class ContactSubTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (ContactSubTypes::cases() as $type) {
            ContactSubType::firstOrCreate(
                ['name' => $type->value],
                ['name' => $type->value]
            );
        }
    }
}
