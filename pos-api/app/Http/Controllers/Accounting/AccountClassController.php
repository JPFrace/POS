<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;

use App\Http\Requests\Accounting\AccountClass\StoreAccountClassRequest;
use App\Http\Resources\Accounting\AccountClassResource;
use App\Repositories\AccountClassRepository;
use Illuminate\Http\Request;
use App\Models\AccountClass;
use Illuminate\Http\Exceptions\HttpResponseException;

class AccountClassController extends Controller
{
    public function __construct(protected AccountClassRepository $repository)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Accounting.Account Class", "List");

        return $this->query($this->repository, AccountClassResource::class, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountClassRequest $request)
    {
        $this->repository->create($request->all());
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccountClass $account_class)
    {
        $request->user()->throwCannot("Accounting.Account Class", "Edit");

        $this->repository->update($request->all(), $account_class->uuid);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountClass $account_class, Request $request)
    {
        $request->user()->throwCannot("Accounting.Account Class", "Delete");

        $this->repository->delete($account_class->uuid);
    }
}
