<?php

namespace Database\Seeders;

use App\Enums\TransType;
use App\Models\FinancialTransCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateFinancialCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'trans_type' => TransType::DISBURSEMENT,
                'code' => 'CDJ',
                'name' => 'Check disbursement Journal',
                'description' => '',
                'creator_id' => 1
            ],
            [
                'trans_type' => TransType::COLLECTION,
                'code' => 'CRJ',
                'name' => 'Cash Receipts Journal',
                'description' => '',
                'creator_id' => 1
            ],
            [
                'trans_type' => TransType::JOURNAL,
                'code' => 'GENJ',
                'name' => 'General Journal',
                'description' => '',
                'creator_id' => 1
            ],
            [
                'trans_type' => TransType::INVOICE,
                'code' => 'SJ',
                'name' => 'Invoice',
                'description' => '',
                'creator_id' => 1
            ]
        ];

        foreach ($data as $row) {
            FinancialTransCode::updateOrCreate([
                'trans_type' => $row['trans_type']
            ], $row);
        }
    }
}
