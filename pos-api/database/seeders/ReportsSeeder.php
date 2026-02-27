<?php

namespace Database\Seeders;

use App\Models\Report;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reports = [
            [
                'name' => 'Statement of Income & Expenses',
                'description' => 'Statement of Income & Expenses',
                'is_inactive' => false,
                'template' => '/features/reports/templates/statement-income-expenses.vue'
            ],
            [
                'name' => 'General Journal',
                'description' => 'General Journal',
                'is_inactive' => false,
                'template' => '/features/reports/templates/general-journal.vue'
            ],
            [
                'name' => 'Trial Balance',
                'description' => 'Trial Balance',
                'is_inactive' => false,
                'template' => '/features/reports/templates/general-ledger-trial-balance.vue'
            ],
            [
                'name' => 'Balance Sheet',
                'description' => 'Balance Sheet',
                'is_inactive' => false,
                'template' => '/features/reports/templates/balance-sheet.vue'
            ],
            [
                'name' => 'Cash Disbursement Journal',
                'description' => 'Cash Disbursement Journal',
                'is_inactive' => false,
                'template' => '/features/reports/templates/cash-disbursement-journal.vue'
            ],
            [
                'name' => 'General Ledger',
                'description' => 'General Ledger',
                'is_inactive' => false,
                'template' => '/features/reports/templates/general-ledger.vue'
            ],
            [
                'name' => 'Customer Ledger',
                'description' => 'Customer Ledger',
                'is_inactive' => false,
                'template' => '/features/reports/templates/customer-ledger.vue'
            ]
        ];

        foreach ($reports as $report) {
            Report::firstOrCreate(['name' => $report['name']], $report);
        }
    }
}
