<?php

namespace App\Http\Controllers\Taxes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Taxes\TaxSetup\TaxSetupUpdateRequest;
use App\Http\Resources\Taxes\TaxSetupResource;
use App\Models\TaxSetup;
use App\Repositories\TaxSetupRepository;
use Illuminate\Http\Request;

class TaxSetupController extends Controller
{
    public function __construct(protected TaxSetupRepository $repository)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        return $this->query($this->repository, TaxSetupResource::class, $request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaxSetupUpdateRequest $request, TaxSetup $tax_setup)
    {

        $this->catch(fn(): mixed => $this->repository->update($request->only([
            'calendar_id',
            'tax_id',
            'period',
            'start_tax_period',
            'start_tax_at',
            'reporting_method',
            'regno',
        ]), $tax_setup->uuid));
    }
}
