<?php

namespace App\Http\Controllers\Business;

use App\Applications\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Business\Bills\StoreBillRequest;
use App\Http\Requests\Business\Bills\UpdateBillRequest;
use App\Http\Requests\Business\Bills\UpdateBillStatusRequest;
use App\Http\Resources\Business\BillResource;
use App\Models\Bill;
use App\Enums\PostingStatus;
use App\Repositories\BillRepository;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class BillsController extends Controller
{
    private Transaction $application;

    public function __construct(protected BillRepository $repository)
    {
        $this->application = new Transaction(Auth::user(), $repository);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Business.Bills", "List");

        return $this->query(
            $this->repository,
            BillResource::class,
            $request
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBillRequest $request)
    {
        return $this->catch(
            fn(): mixed => $this->application->create(
                $request->only([
                    'term_id',
                    'bill_no',
                    'date',
                    'due_date',
                    'remarks',
                    'attachment',
                    'creator_id',
                    'status_id',
                    'vendor_idno',
                    'vendor_name',
                    'vendor_email',
                    'billing_address',
                    'items'
                ])
            ),
            true
        );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBillRequest $request, Bill $bill)
    {
        $this->authorize('update', $bill);

        return $this->catch(
            fn(): mixed => $this->application->update(
                $bill->uuid,
                $request->only([
                    'bill_id',
                    'order_id',
                    'date',
                    'due_date',
                    'remarks',
                    'attachment',
                    'creator_id',
                    'status_id',
                    'vendor_idno',
                    'vendor_name',
                    'vendor_email',
                    'billing_address',
                    'items'
                ]),
            ),
            true
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bill $bill, Request $request)
    {
        $request->user()->throwCannot("Business.Bills", "Delete");

        $this->authorize('delete', $bill);

        $this->repository->delete($bill->uuid);
    }

    /**
     * Update status of bills.
     */
    public function updateStatus(UpdateBillStatusRequest $request)
    {
        $status = PostingStatus::tryFrom($request->status);

        $count = $this->application->updateStatus(
            $status,
            $request->uuids,
        );

        return response()->json([
            'message' => "Successfully updated {$count} bill " . str('entry')->plural($count),
            'count' => $count,
        ]);
    }
}
