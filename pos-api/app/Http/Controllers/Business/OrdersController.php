<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;

use App\Http\Requests\Business\Orders\StoreOrderRequest;
use App\Http\Requests\Business\Orders\UpdateOrderRequest;
use App\Http\Resources\Business\OrderResource;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class OrdersController extends Controller
{

    public function __construct(protected OrderRepository $repository)
    {
        // 
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Business.Purchase Orders", "List");

        return $this->query(
            $this->repository,
            OrderResource::class,
            $request
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $model = $this->catch(fn(): mixed => $this->repository->create($request->only([
            'order_no',
            'date',
            'remarks',
            'attachment',
            'creator_id',
            'vendor_idno',
            'vendor_name',
            'vendor_email',
            'billing_address',
            'items'
        ])), true);
        return [
            'uuid' => $model->uuid
        ];
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $this->catch(fn(): mixed => $this->repository->update($request->only([
            'order_no',
            'date',
            'remarks',
            'attachment',
            'creator_id',
            'vendor_idno',
            'vendor_name',
            'vendor_email',
            'billing_address',
            'items'
        ]), $order->uuid, 'uuid'));

        return [
            'uuid' => $order->uuid
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order, Request $request)
    {
        $request->user()->throwCannot("Business.Purchase Orders", "Delete");

        $this->catch(fn(): mixed => $this->repository->delete($order->uuid, 'uuid'));
    }
}
