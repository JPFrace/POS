<?php

namespace App\Http\Controllers;

use App\Enums\Method;
use App\Http\Controllers\Controller;

class MethodController extends Controller
{
    public function index(): array
    {
        $methodArray = array_map(
            fn(Method $month) => [
                'name' => $month->name,
                'value' => $month->value,
            ],
            Method::cases()
        );

        return $methodArray;
    }
}