<?php

namespace App\Http\Controllers\Business;

use App\Applications\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Business\DepositResource;
use App\Http\Requests\Business\Deposits\DepositStoreRequest;
use App\Http\Requests\Business\Deposits\DepositUpdateRequest;
use App\Models\Deposit;
use App\Repositories\DepositsRepository;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class DepositsController extends Controller
{
    private Transaction $application;

    public function __construct(protected DepositsRepository $repository)
    {
        $this->application = new Transaction(Auth::user(), $repository);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Business.Deposits", "List");

        return $this->query(
            $this->repository,
            DepositResource::class,
            $request
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepositStoreRequest $request)
    {
        return $this->catch(
            fn(): mixed => $this->application->create(
                $request->only([
                    'ref_no',
                    'date',
                    'remarks',
                    'cash_bank_id',
                    'attachment',
                    'creator_id',
                    'items',
                ]),
            ),
            true
        );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(DepositUpdateRequest $request, Deposit $deposit)
    {
        return $this->catch(
            fn(): mixed => $this->application->update(
                $deposit->uuid,
                $request->only([
                    'ref_no',
                    'date',
                    'remarks',
                    'cash_bank_id',
                    'attachment',
                    'creator_id',
                    'items',
                ]),
            ),
            true
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Deposit $deposit)
    {
        $request->user()->throwCannot("Business.Bank Deposits", "Delete");

        $this->application->delete($deposit->uuid);
    }
}
