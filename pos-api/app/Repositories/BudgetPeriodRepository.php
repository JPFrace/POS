<?php

namespace App\Repositories;

use App\Models\BudgetDetailPeriod;
use App\Models\ChartAccount;
use App\Supports\Utils\Amount;
use Illuminate\Support\Facades\DB;

class BudgetPeriodRepository extends Repository
{
    public function __construct(protected BudgetDetailPeriod $model) {}
}
