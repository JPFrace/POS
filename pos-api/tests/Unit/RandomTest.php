<?php

namespace Tests\Unit;

use App\Models\ChartAccount;
use App\Services\Reports\BeginningEndingBalance;
use Carbon\Carbon;
use Tests\TestCase;

class RandomTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_beining_balances(): void
    {
        foreach (ChartAccount::get() as $account) {
            BeginningEndingBalance::generate(
                $account,
                Carbon::parse('2025-01-01'),
                Carbon::parse('2025-12-31'),
            )->daily();
        }

    }
}
