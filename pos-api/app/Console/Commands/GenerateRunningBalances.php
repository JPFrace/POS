<?php

namespace App\Console\Commands;

use App\Models\ChartAccount;
use App\Models\ChartAccountBalance;
use App\Services\Reports\BeginningEndingBalance;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateRunningBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-running-balances {--start=} {--end=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ChartAccountBalance::truncate();

        $start = $this->option('start');
        $end = $this->option('end');

        foreach (ChartAccount::get() as $account) {
            BeginningEndingBalance::generate(
                $account,
                Carbon::parse($start),
                Carbon::parse($end),
            )->daily();
        }
    }
}
