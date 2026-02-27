<?php

namespace App\Http\Controllers;

use App\Enums\TaxTypes;
use App\Http\Controllers\Controller;

class TaxTypeController extends Controller
{
    public function index(): array
    {
        $methodArray = array_map(
            fn(TaxTypes $type) => [
                'name' => $type->name,
                'value' => $type->value,
            ],
            TaxTypes::cases()
        );

        return $methodArray;
    }
}