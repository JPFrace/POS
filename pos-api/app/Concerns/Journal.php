<?php

namespace App\Concerns;

use App\Contracts\Business\Transactable;
use App\Contracts\Journals\Journal as JournalsJournal;
use App\Contracts\Supports\Handleable;

class Journal implements JournalsJournal
{
    /**
     * @inheritDoc
     */
    public static function createable(Transactable $transaction): Handleable
    {
        return (new Journals\Creatable($transaction));
    }

    /**
     * @inheritDoc
     */
    public static function deletable(Transactable $transaction): Handleable
    {
        return (new Journals\Deletable($transaction));
    }

    /**
     * @inheritDoc
     */
    public static function reversable(Transactable $transaction): Handleable
    {
        return (new Journals\Reversable($transaction));
    }
}