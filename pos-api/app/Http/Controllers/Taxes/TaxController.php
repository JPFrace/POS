<?php

namespace App\Http\Controllers\Taxes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\Product\ProductStoreRequest;
use App\Http\Requests\Taxes\Tax\TaxStoreRequest;
use App\Http\Requests\Taxes\Tax\TaxUpdateRequest;
use App\Http\Resources\Taxes\TaxResource;
use App\Models\Tax;
use App\Repositories\TaxRepository;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function __construct(protected TaxRepository $repository)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->query($this->repository, TaxResource::class, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaxStoreRequest $request)
    {
        \Log::info("Inside");
        return $this->catch(fn(): mixed => $this->repository->create($request->only([
            'tax_agency_id',
            'code',
            'name',
            'description',
            'rate',
            'chart_account_id',
            'class_id',
            'type',
            'rate_type',
            'parent_id'
        ])));
    }

    /**
     * Display the specified resource.
     */
    public function show(Tax $tax)
    {
        return $this->catch(fn(): mixed => $this->repository->findByUuid($tax->uuid), expectResponse: true);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaxUpdateRequest $request, Tax $tax)
    {
        \Log::info('TaxController@update - entered', [
            'tax_uuid' => $tax->uuid,
            'tax_id' => $tax->id,
        ]);

        \Log::info('Update request payload (filtered)', [
            'data' => $request->only([
                'tax_agency_id',
                'code',
                'name',
                'description',
                'rate',
                'chart_account_id',
                'class_id',
                'type',
                'rate_type',
                'parent_id',
            ]),
        ]);

        return $this->catch(function () use ($request, $tax): mixed {
            \Log::info('Updating tax record...', [
                'tax_uuid' => $tax->uuid,
            ]);

            $updated = $this->repository->update(
                $request->only([
                    'tax_agency_id',
                    'code',
                    'name',
                    'description',
                    'rate',
                    'chart_account_id',
                    'class_id',
                    'type',
                    'rate_type',
                    'parent_id',
                ]),
                $tax->uuid
            );

            \Log::info('Tax record updated successfully', [
                'tax_uuid' => $tax->uuid,
            ]);

            return $updated;
        });
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tax $tax, Request $request)
    {
        // $request->user()->throwCannot("Products & Services.Catalogue", "Delete");

        $this->catch(fn(): mixed => $this->repository->delete($tax->uuid));
    }
}
