<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Enums\Period;
class PeriodController extends Controller
{
    public function index(): array
    {
        $periodArray = array_map(
            fn(Period $month) => [
                'name' => $month->name,
                'value' => $month->value,
            ],
            Period::cases()
        );

        return $periodArray;
    }
}