<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentTypes = [
            [
                'name' => 'Check',
                'icon' => 'wallet',
                'description' => 'Payment via check',
                'short_desc' => 'Check',
                'created_by' => 1
            ],
            [
                'name' => 'Cash',
                'icon' => 'bill',
                'description' => 'Payment via cash',
                'short_desc' => 'Cash',
                'created_by' => 1
            ],
            [
                'name' => 'Petty Cash',
                'icon' => 'office-bag',
                'description' => 'Payment via petty cash',
                'short_desc' => 'Petty Cash',
                'created_by' => 1
            ],
        ];
        foreach ($paymentTypes as $type) {
            PaymentType::firstOrCreate(
                ['name' => $type['name']],
                [
                    'icon' => $type['icon'],
                    'description' => $type['description'],
                    'short_desc' => $type['short_desc'],
                    'created_by' => $type['created_by']
                ]
            );
        }
    }
}
