<?php

namespace App\Listeners\Business\OfficialReceipts;

use App\Enums\TransType;
use App\Events\Business\OfficialReceipts\OfficialReceiptCreated;
use App\Models\InvoiceDetail;
use DB;

class InvoiceOpenBalance
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OfficialReceiptCreated $event): void
    {
        $model = $event->model;

        if ($model->trans_type == TransType::INVOICE) {
            $invoice_item = InvoiceDetail::where([
                'invoice_id' => $model->ref_no,
                'product_id' => $model->product_id
            ])->first();

            if ($invoice_item) {
                $invoice_item->paid += $model->sub_total;
                $invoice_item->save();
            }
        }
    }
}
