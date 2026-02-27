<?php

namespace App\Models\Concerns;

trait DeliveredCalculate
{
    /**
     * Summary of calculate
     * @return void
     */
    public function refreshDelivered()
    {
        foreach ($this->details as $billItem) {
            foreach ($billItem->order->details as $orderItem) {
                $total = $orderItem->parent->getDeliveredTotalItems($orderItem->product_id);
                $orderItem->delivered = $total;
                $orderItem->save();
            }
        }
    }
}