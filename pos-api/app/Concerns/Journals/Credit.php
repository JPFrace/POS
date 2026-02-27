<?php

namespace App\Concerns\Journals;


use App\Contracts\Business\Transactable;
use App\Contracts\Supports\Makeable;

class Credit implements Makeable
{
    public function __construct(protected Transactable $transaction)
    {

    }

    public function make()
    {
        $data = collect();

        foreach ($this->transaction->details()->get() as $detail) {
            foreach ($detail->creditRaw() as $row) {
                $data->push([
                    'trans_type' => $row->detail()->type(),
                    'ref_no' => $row->detail()->refNo(),
                    'chart_account_id' => $row->account()->id,
                    'debit' => 0,
                    'credit' => $row->amount()->float(),
                    ...$row->detail()->toArray()
                ]);
            }
        }

        $data = $data->unique('chart_account_id')->values();

        return $data;
    }
}