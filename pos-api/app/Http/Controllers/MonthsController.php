<?php

namespace App\Http\Controllers;

use App\Enums\Months;
use App\Http\Controllers\Controller;

class MonthsController extends Controller
{
    public function index(): array
    {
        $monthsArray = array_map(
            fn(Months $month) => [
                'name' => $month->name,
                'value' => $month->value,
            ],
            Months::cases()
        );

        return $monthsArray;
    }
}
