<?php

namespace Database\Seeders;

use App\Enums\AccountCategory;
use App\Enums\NormalBalance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => AccountCategory::ASSETS->value,
                'description' => '',
                'normal_balance' => NormalBalance::DEBIT
            ],
            [
                'name' => AccountCategory::LIABILITIES->value,
                'description' => '',
                'normal_balance' => NormalBalance::CREDIT
            ],
            [
                'name' => AccountCategory::EQUITY->value,
                'description' => '',
                'normal_balance' => NormalBalance::CREDIT
            ],
            [
                'name' => AccountCategory::REVENUE->value,
                'description' => '',
                'normal_balance' => NormalBalance::CREDIT
            ],
            [
                'name' => AccountCategory::EXPENSES->value,
                'description' => '',
                'normal_balance' => NormalBalance::DEBIT
            ],
        ];
        foreach ($data as $row) {
            \App\Models\AccountCategory::firstOrCreate([
                'name' => $row['name']
            ], $row);
        }
    }
}
