<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\AccountClass\StoreAccountClassRequest;
use App\Http\Resources\Business\BillTermResource;
use App\Repositories\BillTermRepository;
use Illuminate\Http\Request;
use App\Models\AccountClass;
use Illuminate\Http\Exceptions\HttpResponseException;

class BillTermsController extends Controller
{
    public function __construct(protected BillTermRepository $repository)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->query($this->repository, BillTermResource::class, $request);
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
        $this->repository->update($request->all(), $account_class->uuid);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountClass $account_class)
    {
        auth()->user()->canDelete("Bills");
        $this->catch(fn(): mixed => $this->repository->delete($account_class->uuid));
    }
}
