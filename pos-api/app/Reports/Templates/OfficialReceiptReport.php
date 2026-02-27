<?php

namespace App\Reports\Templates;

use App\Models\OfficialReceipt;

class OfficialReceiptReport
{
    public function __construct(protected string $uuid) {}

    public static function make($uuid)
    {
        return (new self($uuid))->handle();
    }

    public function handle()
    {
        $official_receipt = OfficialReceipt::select('id', 'uuid', 'customer_name', 'billing_address')
            ->with([
                'journals' => function ($query) {
                    $query->select('id', 'transactable_type', 'transactable_id', 'description', 'posted_at')
                        ->whereNotNull('description')
                        ->where('credit', '>', 0);
                },
                'details:id,or_id,quantity,rate',
                'denominations.deposit_account:id,name,code',
                'denominations.payment_method:id,name,code'
            ])
            ->where("uuid", $this->uuid)
            ->first();

        if (!$official_receipt) {
            throw new \Exception("Official Receipt does not exist.");
        }

        $official_receipt->makeHidden('url');

        $official_receipt->journals->each(
            fn($journal) =>
            $journal->transactable?->makeHidden('url')
        );

        return [
            'official_receipt' => $official_receipt,
        ];
    }
}
