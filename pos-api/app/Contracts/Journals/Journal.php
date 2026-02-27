<?php

namespace App\Contracts\Journals;

use App\Contracts\Business\Transactable;
use App\Contracts\Supports\Handleable;

interface Journal
{
    /**
     * Create journal entries
     * @param \App\Contracts\Business\Transactable $transaction
     * @return \App\Contracts\Supports\Handleable
     */
    public static function createable(Transactable $transaction): Handleable;

    /**
     * Delete journal entries
     * @param \App\Contracts\Business\Transactable $transaction
     * @return \App\Contracts\Supports\Handleable
     */
    public static function deletable(Transactable $transaction): Handleable;

    /**
     * Reverse journal entries
     * @param \App\Contracts\Business\Transactable $transaction
     * @return \App\Contracts\Supports\Handleable
     */
    public static function reversable(Transactable $transaction): Handleable;

}