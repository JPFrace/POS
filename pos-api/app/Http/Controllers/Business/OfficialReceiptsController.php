<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\Business\OfficialReceipts\StoreOfficialReceiptRequest;
use App\Http\Requests\Business\OfficialReceipts\UpdateOfficialReceiptRequest;
use App\Http\Requests\Business\OfficialReceipts\UpdateOfficialReceiptStatusRequest;
use App\Http\Resources\Business\OfficialReceiptResource;
use App\Models\OfficialReceipt;
use App\Enums\PostingStatus;
use App\Repositories\OfficialReceiptRepository;
use App\Applications\Transaction;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class OfficialReceiptsController extends Controller
{

    private Transaction $application;

    public function __construct(protected OfficialReceiptRepository $repository)
    {
        $this->application = new Transaction(Auth::user(), $repository);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Business.Receive Money", "List");

        return $this->query(
            $this->repository,
            OfficialReceiptResource::class,
            $request
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOfficialReceiptRequest $request)
    {
        return $this->catch(
            fn(): mixed => $this->application->create(
                $request->only([
                    'or_no',
                    'ref_no',
                    'date',
                    'remarks',
                    'attachment',
                    'creator_id',
                    'status_id',
                    'customer_idno',
                    'customer_name',
                    'customer_email',
                    'billing_address',
                    'amount',
                    'items',
                    'dimensions',
                    'denominations'
                ]),
            ),
            true
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOfficialReceiptRequest $request, OfficialReceipt $official_receipt)
    {
        $this->authorize('update', $official_receipt);

        return $this->catch(
            fn(): mixed => $this->application->update(
                $official_receipt->uuid,
                $request->only([
                    'or_no',
                    'ref_no',
                    'date',
                    'remarks',
                    'attachment',
                    'creator_id',
                    'status_id',
                    'customer_idno',
                    'customer_name',
                    'customer_email',
                    'billing_address',
                    'amount',
                    'items',
                    'dimensions',
                    'denominations'
                ]),
            ),
            false
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, OfficialReceipt $official_receipt)
    {
        $request->user()->throwCannot("Business.Receive Money", "Delete");

        $this->authorize('delete', $official_receipt);

        $this->application->delete($official_receipt->uuid);
    }

    public function updateStatus(UpdateOfficialReceiptStatusRequest $request)
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
