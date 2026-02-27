<?php

namespace App\Http\Controllers\Business;

use App\Applications\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Business\Payments\PaymentsStoreRequest;
use App\Http\Requests\Business\Payments\PaymentsUpdateRequest;
use App\Http\Requests\Business\Payments\UpdatePaymentStatusRequest;
use App\Http\Resources\Business\PaymentResource;
use App\Models\Payment;
use App\Enums\PostingStatus;
use App\Repositories\PaymentsRepository;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class PaymentsController extends Controller
{

    private Transaction $application;

    public function __construct(protected PaymentsRepository $repository)
    {
        $this->application = new Transaction(Auth::user(), $repository);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Business.Make Payments", "List");

        return $this->query(
            $this->repository,
            PaymentResource::class,
            $request
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentsStoreRequest $request)
    {
        return $this->catch(
            fn(): mixed => $this->application->create(
                $request->only([
                    // Create the payment record
                    'ref_no',
                    'check_no',
                    'date',
                    'payee_idno',
                    'payment_method_id',
                    'remarks',
                    'cash_bank_id',
                    'payee_name',
                    'payee_email',
                    'payee_address',
                    'attachment',
                    'creator_id',
                    'status_id',
                    'items',
                    'dimensions'
                ])
            ),
            true
        );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(PaymentsUpdateRequest $request, Payment $payment)
    {
        $this->authorize('update', $payment);

        return $this->catch(
            fn(): mixed => $this->application->update(
                $payment->uuid,
                $request->only([
                    'ref_no',
                    'check_no',
                    'date',
                    'payee_idno',
                    'payment_method_id',
                    'remarks',
                    'cash_bank_id',
                    'payee_name',
                    'payee_email',
                    'payee_address',
                    'attachment',
                    'creator_id',
                    'status_id',
                    'items',
                    'dimensions'
                ]),
            ),
            true
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Payment $payment)
    {
        $request->user()->throwCannot("Business.Make Payments", "Delete");

        $this->authorize('delete', $payment);

        $this->application->delete($payment->uuid);
    }

    public function updateStatus(UpdatePaymentStatusRequest $request)
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
