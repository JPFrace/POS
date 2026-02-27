<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\AccountUsageType\AccountUsageTypeStoreRequest;
use App\Http\Requests\Accounting\AccountUsageType\AccountUsageTypeUpdateRequest;
use App\Http\Resources\Accounting\AccountUsageTypeResource;
use App\Models\AccountUsageType;
use App\Repositories\AccountUsageTypesRepository;
use Illuminate\Http\Request;

class AccountUsageTypesController extends Controller
{
    public function __construct(protected AccountUsageTypesRepository $repository)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $request->user()->throwCannot("Accounting.Account Types.List");

        return $this->query($this->repository, AccountUsageTypeResource::class, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AccountUsageTypeStoreRequest $request)
    {
        $this->catch(fn() => $this->repository->create($request->only([
            'code',
            'name',
            'description'
        ])));
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
    public function update(AccountUsageTypeUpdateRequest $request, AccountUsageType $account_usage_type)
    {
        $this->catch(fn() => $this->repository->update($request->only([
            'code',
            'name',
            'description'
        ]), $account_usage_type->uuid, 'uuid'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountUsageType $account_usage_type)
    {
        $this->catch(fn() => $this->repository->delete($account_usage_type->uuid, 'uuid'));
    }
}
