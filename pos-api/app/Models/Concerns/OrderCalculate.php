<?php

namespace App\Models\Concerns;

use App\Enums\PoStatus;

trait OrderCalculate
{
    /**
     * Get sum of delivered total items
     * @param int $productId
     * @return float
     */
    public function getDeliveredTotalItems(int $productId): float
    {
        return $this->delivered()->where("product_id", $productId)->sum('quantity');
    }

    /**
     * Update order status
     * @return void
     */
    public function refreshStatus(): void
    {
        $ordered = $this->details->sum("quantity");
        $delivered = $this->delivered->sum("quantity");

        $this->status = PoStatus::OPEN;

        // For complete delivery
        if ($ordered <= $delivered) {
            $this->status = PoStatus::COMPLETED;
        }

        // For partial delivery where items orders are not completely delivered
        if ($ordered > $delivered) {
            $this->status = PoStatus::PARTIAL;
        }

        $this->save();
    }
}