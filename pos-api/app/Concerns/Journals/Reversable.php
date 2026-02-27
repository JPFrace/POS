<?php

namespace App\Concerns\Journals;

use App\Concerns\Journal;
use App\Contracts\Business\Transactable;
use App\Contracts\Supports\Handleable;

class Reversable implements Handleable
{
    public function __construct(protected Transactable $transaction)
    {

    }

    public function handle()
    {
        $transaction = $this->transaction;

        // Delete existing journal entries
        Journal::deletable($transaction);

        // Reverse entries
        foreach ((new Debit($transaction))->make() as $row) {
            $transaction->journals()->create([
                ...$row,
                'debit' => 0,
                'credit' => $row['debit']
            ]);
        }

        // Reverse entries
        foreach ((new Credit($transaction))->make() as $row) {
            $transaction->journals()->create([
                ...$row,
                'debit' => $row['credit'],
                'credit' => 0
            ]);
        }

    }
}