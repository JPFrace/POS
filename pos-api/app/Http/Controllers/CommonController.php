<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PeriodController;
use App\Http\Controllers\Security\RolesController;
use App\Supports\Common\RegisterControllerKeys;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    use RegisterControllerKeys;

    /**
     * Register your controller
     * @var array
     */
    protected function registers()
    {
        return [
            RolesController::class => fn(Request $request) => [$request],
            PeriodController::class => fn(Request $request) => [$request],
            MonthsController::class => fn(Request $request) => [$request],
            MethodController::class => fn(Request $request) => [$request],
            TaxTypeController::class => fn(Request $request) => [$request],
            RateTypeController::class => fn(Request $request) => [$request],

        ];
    }

    /**
     * Load registered controller index
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function index(Request $request)
    {
        return $this->loader($request);
    }
}
