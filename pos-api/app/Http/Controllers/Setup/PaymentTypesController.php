<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setup\PaymentTypes\PaymentTypeUpdateRequest;
use App\Http\Resources\Setup\PaymentTypeResouce;
use App\Models\PaymentType;
use App\Http\Requests\Setup\PaymentTypes\PaymentTypeStoreRequest;
use Illuminate\Http\Request;
use App\Repositories\PaymentTypesRepository;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaymentTypesController extends Controller
{
    public function __construct(protected PaymentTypesRepository $repository)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Setup.Payment Types", "List");

        return $this->query($this->repository, PaymentTypeResouce::class, $request);
    }

    /** 
     * Store a newly created resource in storage.
     */
    public function store(PaymentTypeStoreRequest $request)
    {
        return $this->catch(fn(): mixed => $this->repository->create($request->only([
            'name',
            'code',
            'description',
            'short_desc',
            'inactive',
        ])), expectResponse: false);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PaymentTypeUpdateRequest $request, PaymentType $payment_type)
    {
        $this->repository->update($request->only([
            'name',
            'code',
            'description',
            'short_desc',
            'inactive',
        ]), $payment_type->uuid, 'uuid');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentType $payment_type, Request $request)
    {
        $request->user()->throwCannot("Setup.Payment Types", "Delete");

        $this->repository->delete($payment_type->uuid, 'uuid');
    }
}
