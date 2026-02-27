<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setup\WithholdingTaxes\StoreWithholdingTaxTypeRequest;
use App\Http\Requests\Setup\WithholdingTaxes\UpdateWithholdingTaxRequest;
use App\Http\Resources\Setup\WithholdingTaxTypesResource;
use App\Models\WithholdingTaxType;
use App\Repositories\WithholdingTaxTypeRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class WithholdingTaxTypesController extends Controller
{
    public function __construct(protected WithholdingTaxTypeRepository $repository)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->query($this->repository, WithholdingTaxTypesResource::class, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWithholdingTaxTypeRequest $request)
    {
        $this->repository->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreWithholdingTaxTypeRequest $request, WithholdingTaxType $withholding_tax_type)
    {
        $this->repository->update($request->all(), $withholding_tax_type->uuid);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WithholdingTaxType $withholding_tax_type)
    {
        auth()->user()->canDelete("Withholding Tax");
        $withholding_tax_type->delete();
    }
}
