<?php

namespace App\Http\Controllers\Taxes;

use App\Http\Controllers\Controller;
use App\Http\Resources\Taxes\TaxAgencyResource;
use App\Repositories\TaxAgencyRepository;
use Illuminate\Http\Request;

class TaxAgencyController extends Controller
{
    public function __construct(protected TaxAgencyRepository $repository)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        return $this->query($this->repository, TaxAgencyResource::class, $request);
    }
}