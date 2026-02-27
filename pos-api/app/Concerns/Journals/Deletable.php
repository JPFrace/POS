<?php

namespace App\Concerns\Journals;

use App\Contracts\Business\Transactable;
use App\Contracts\Supports\Handleable;
use App\Events\Journals\JournalDeleted;

class Deletable implements Handleable
{
    public function __construct(protected Transactable $transaction)
    {

    }

    /**
     * Delete journal
     * @return void
     */
    public function handle()
    {
        $transaction = $this->transaction;

        foreach ($transaction->details()->get() as $detail) {
            foreach ($detail->debit()->get() as $entry) {
                $entry->delete();
                event(new JournalDeleted(
                    $entry->chartAccount,
                    $entry->posted_at,
                    $entry->posted_at
                ));
            }

            foreach ($detail->credit()->get() as $entry) {
                $entry->delete();
                event(new JournalDeleted(
                    $entry->chartAccount,
                    $entry->posted_at,
                    $entry->posted_at
                ));
            }
        }
    }
}