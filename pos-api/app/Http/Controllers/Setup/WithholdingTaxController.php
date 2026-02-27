<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setup\WithholdingTaxes\StoreWithholdingTaxRequest;
use App\Http\Requests\Setup\WithholdingTaxes\UpdateWithholdingTaxRequest;
use App\Http\Resources\Setup\WithholdingTaxResource;
use App\Models\WithholdingTax;
use App\Repositories\WithholdingTaxRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class WithholdingTaxController extends Controller
{
    public function __construct(protected WithholdingTaxRepository $withholdingtax)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Setup.Withholding Taxes", "List");

        return $this->query($this->withholdingtax, WithholdingTaxResource::class, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWithholdingTaxRequest $request)
    {
        $this->withholdingtax->create($request->all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWithholdingTaxRequest $request, WithholdingTax $withholding_tax)
    {
        $this->withholdingtax->update($request->only([
            'code',
            'description',
            'rate',
            'type_id',
            'payer_type_id',
            'is_inactive',
        ]), $withholding_tax->uuid);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WithholdingTax $withholding_tax, Request $request)
    {
        $request->user()->throwCannot("Setup.Withholding Taxes", "Delete");

        try {
            $withholding_tax->delete();
        } catch (QueryException $e) {
            if ($e->getCode() == '23000') {
                return response()->json([
                    'message' => 'Cannot delete Withholding Tax: ' . $withholding_tax->code . '\n' . $withholding_tax->description . '. It is referenced by other records.'
                ], 409);
            }
            throw $e;
        }
    }
}
