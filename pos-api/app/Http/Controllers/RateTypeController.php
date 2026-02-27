<?php

namespace App\Http\Controllers;

use App\Enums\RateType;
use App\Http\Controllers\Controller;

class RateTypeController extends Controller
{
    public function index(): array
    {
        $methodArray = array_map(
            fn(RateType $rate_type) => [
                'name' => $rate_type->name,
                'value' => $rate_type->value,
            ],
            RateType::cases()
        );

        return $methodArray;
    }
}