<?php

namespace App\Reports;

use App\Enums\AccountCategory;
use App\Enums\AccountUsageType;
use App\Models\AccountClass;
use App\Repositories\ReportsRepository;
use App\Repositories\AccountClassRepository;
use App\Repositories\ChartAccountsRepository;
use Carbon\Carbon;
use DB;

class LedgerQuery
{
    protected ChartAccountsRepository $charts;
    protected AccountClassRepository $classifications;
    protected ReportsRepository $reports;

    public function __construct()
    {
        $this->reports = app(ReportsRepository::class);
        $this->classifications = app(AccountClassRepository::class);
        $this->charts = app(ChartAccountsRepository::class);
    }

    public static function make(array $dates, ?\closure $classWhere = null, ?\closure $chartWhere = null)
    {
        return (new Types\Generator(
            $dates,
            $classWhere,
            $chartWhere
        ))->handle();
    }
}