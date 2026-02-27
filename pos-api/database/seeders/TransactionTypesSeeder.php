<?php

namespace Database\Seeders;

use App\Enums\TransType;
use App\Models\TransType as TransactionType;
use Illuminate\Database\Seeder;

class TransactionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (TransType::cases() as $type) {
            TransactionType::firstOrCreate(['name' => $type->name], ['name' => $type->name, 'code' => $type]);
        }
    }
}
