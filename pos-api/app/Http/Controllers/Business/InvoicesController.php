<?php

namespace App\Http\Controllers\Business;

use App\Applications\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Business\Invoices\StoreInvoiceRequest;
use App\Http\Requests\Business\Invoices\UpdateInvoiceRequest;
use App\Http\Requests\Business\Invoices\UpdateInvoiceStatusRequest;
use App\Http\Resources\Business\InvoiceResource;
use App\Models\Invoice;
use App\Enums\InvoiceStatusEnum;
use App\Enums\PostingStatus;
use App\Repositories\InvoiceRepository;
use App\Services\Transactions\InvoiceService;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class InvoicesController extends Controller
{

    private Transaction $application;

    public function __construct(protected InvoiceRepository $repository)
    {
        $this->application = new Transaction(Auth::user(), $repository);
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Business.Invoice", "List");

        return $this->query(
            $this->repository,
            InvoiceResource::class,
            $request
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        return $this->catch(
            fn(): mixed => $this->application->create(
                $request->only([
                    'invoice_no',
                    'date',
                    'due_date',
                    'remarks',
                    'attachment',
                    'creator_id',
                    'status_id',
                    'customer_idno',
                    'customer_name',
                    'customer_email',
                    'billing_address',
                    'payment_method_id',
                    'items'
                ])
            ),
            true
        );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        return $this->catch(
            fn(): mixed => $this->application->update(
                $invoice->uuid,
                $request->only([
                    'invoice_no',
                    'date',
                    'due_date',
                    'remarks',
                    'attachment',
                    'creator_id',
                    'status_id',
                    'customer_idno',
                    'customer_name',
                    'customer_email',
                    'billing_address',
                    'payment_method_id',
                    'deposit_id',
                    'items'
                ]),
            ),
            true
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice, Request $request)
    {
        $request->user()->throwCannot("Business.Invoice", "Delete");

        $this->authorize('delete', $invoice);

        $this->application->delete($invoice->uuid);
    }

    public function updateStatus(UpdateInvoiceStatusRequest $request)
    {
        $status = PostingStatus::tryFrom($request->status);

        $count = $this->application->updateStatus(
            $status,
            $request->uuids,
        );

        return response()->json([
            'message' => "Successfully updated {$count} official " . str('receipt')->plural($count),
            'count' => $count,
        ]);
    }
}
