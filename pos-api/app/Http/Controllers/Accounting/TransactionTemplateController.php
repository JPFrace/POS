<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\TransactionTemplate\TransactionTemplateStoreRequest;
use App\Http\Requests\Accounting\TransactionTemplate\TransactionTemplateUpdateRequest;
use App\Models\TransactionTemplate;
use Illuminate\Http\Request;
use App\Repositories\TransactionTemplateRepository;
use App\Http\Resources\Accounting\TransactionTemplateResource;

class TransactionTemplateController extends Controller
{
    public function __construct(protected TransactionTemplateRepository $repository)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): mixed
    {
        $request->user()->throwCannot("Accounting.Transaction Templates", "List");

        return $this->query($this->repository, TransactionTemplateResource::class, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionTemplateStoreRequest $request): void
    {
        $this->catch(fn(): TransactionTemplate => $this->repository->create($request->only([
            'name',
            'description',
            'is_inactive',
            'details',
        ])));
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionTemplate $transactionTemplate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionTemplateUpdateRequest $request, TransactionTemplate $transactionTemplate): void
    {
        $this->catch(fn(): TransactionTemplate => $this->repository->update($request->only([
            'name',
            'description',
            'is_inactive',
            'details',
        ]), $transactionTemplate->uuid));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionTemplate $transactionTemplate,Request $request): void
    {
        $request->user()->throwCannot("Accounting.Transaction Templates", "Delete");

        $this->catch(
            fn(): bool =>
            $this->repository->delete($transactionTemplate->uuid)
        );
    }
}
