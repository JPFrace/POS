<?php

namespace App\Repositories;

use App\Models\Calendar;

class CalendarRepository extends Repository
{
    use Conditions\CalendarConditions;
    public function __construct(protected Calendar $model)
    {

    }
}