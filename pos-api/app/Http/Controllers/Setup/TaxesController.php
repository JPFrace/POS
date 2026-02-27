<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Resources\Setup\TaxResource;
use App\Models\WithholdingTax;
use App\Repositories\TaxRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class TaxesController extends Controller
{
    public function __construct(protected TaxRepository $tax)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $request->user()->throwCannot("Setup.Taxes", "List");

        return $this->query($this->tax, TaxResource::class, $request);
    }
}
