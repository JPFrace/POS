<?php

namespace Database\Seeders;

use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (PaymentStatusEnum::cases() as $status) {
            DB::table('payment_statuses')->updateOrInsert(
                ['id' => $status->value],
                [
                    'uuid' => Str::uuid(),
                    'name' => $status->label(),
                    'description' => $status->description(),
                ]
            );
        }
    }
}
