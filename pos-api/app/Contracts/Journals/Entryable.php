<?php

namespace App\Contracts\Journals;

use Illuminate\Database\Eloquent\Casts\Attribute;

interface Entryable
{
    /**
     * Create Debit Entry
     * @return array
     */
    public function debitRaw(): array;

    /**
     * Created Credit Entry
     * @return array
     */
    public function creditRaw(): array;

    /**
     * Debit records
     * @return mixed
     */
    public function debit();

    /**
     * Credit records
     * @return mixed
     */
    public function credit();

}