<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;

use App\Http\Requests\Accounting\AccountTypes\StoreAccountTypeRequest;
use App\Http\Requests\Accounting\AccountTypes\UpdateAccountTypeRequest;
use App\Http\Resources\Accounting\AccountTypeResource;
use App\Repositories\AccountTypesRepository;
use Illuminate\Http\Request;
use App\Models\AccountType;
use Illuminate\Http\Exceptions\HttpResponseException;

class AccountTypesController extends Controller
{
    public function __construct(protected AccountTypesRepository $repository)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Accounting.Account Types", "List");

        return $this->query($this->repository, AccountTypeResource::class, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountTypeRequest $request)
    {
        $this->catch(fn() => $this->repository->create($request->only([
            'name',
            'description',
            'seq',
            'is_inactive',
            'category_id'
        ])));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountTypeRequest $request, AccountType $account_type)
    {
        $this->catch(fn() => $this->repository->update($request->only([
            'name',
            'description',
            'seq',
            'is_inactive',
            'category_id'
        ]), $account_type->uuid));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountType $account_type, Request $request)
    {
        $request->user()->throwCannot("Accounting.Account Types", "Delete");

        $this->repository->delete($account_type->uuid);
    }
}
