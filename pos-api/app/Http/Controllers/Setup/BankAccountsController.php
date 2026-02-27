<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Resources\Setup\BankAccountsResource;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Http\Requests\Setup\BankAccounts\StoreBankAccountRequest;
use App\Http\Requests\Setup\BankAccounts\UpdateBankAccountRequest;
use App\Repositories\BankAccountRepository;
use Illuminate\Http\Exceptions\HttpResponseException;

class BankAccountsController extends Controller
{

    public function __construct(protected BankAccountRepository $bankAccountRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Setup.Bank Accounts", "List");

        return $this->query($this->bankAccountRepository, BankAccountsResource::class, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBankAccountRequest $request)
    {
        $this->catch(fn(): mixed => $this->bankAccountRepository->create($request->only([
            'account_number',
            'account_name',
            'bank_name',
            'bank_code',
            'account_id',
            'is_inactive',
        ])));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBankAccountRequest $request, BankAccount $bankAccount)
    {
        $this->catch(fn(): mixed => $this->bankAccountRepository->update($request->only([
            'account_number',
            'account_name',
            'bank_name',
            'bank_code',
            'account_id',
            'is_inactive',
        ]), $bankAccount->uuid));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankAccount $bankAccount, Request $request)
    {
        $request->user()->throwCannot("Setup.Bank Accounts", "Delete");

        $this->bankAccountRepository->delete($bankAccount->id, 'id');
    }
}
