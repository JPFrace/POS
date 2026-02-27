<?php

namespace App\Repositories;

use App\Models\Report;

class ReportsRepository extends Repository
{
    use Conditions\ReportConditions;

    public function __construct(protected Report $model)
    {

    }
}