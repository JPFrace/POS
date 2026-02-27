<?php

namespace App\Concerns\Journals;

use App\Concerns\Exceptions\NoTaxException;
use App\Concerns\Journal;
use App\Contracts\Business\CanCollectSalesTax;
use App\Contracts\Business\CanWithholdTax;
use App\Contracts\Business\HasCreditableTax;
use App\Contracts\Business\HasCreditableWithholdingTax;
use App\Contracts\Business\HasNegativeAsPayable;
use App\Contracts\Business\HasPayable;
use App\Contracts\Business\Journalable;
use App\Contracts\Business\Transactable;
use App\Contracts\Supports\Handleable;
use App\Supports\Dto\Journals\JournalEntry;

class Creatable implements Handleable
{
    public function __construct(protected Transactable $transaction)
    {

    }

    public function handle()
    {
        $transaction = $this->transaction;

        $this->validate();

        // Delete existing journal entries
        Journal::deletable($transaction)->handle();

        // Create debit entries
        foreach ((new Debit($transaction))->make()->all() as $row) {
            $transaction->journals()->create($row);
        }

        foreach ((new Credit($transaction))->make()->all() as $row) {
            $transaction->journals()->create($row);
        }
    }

    private function validate()
    {
        $transaction = $this->transaction;

        foreach ($transaction->details()->get() as $detail) {
            if (
                $detail instanceof HasNegativeAsPayable &&
                $detail->rate < 0 && empty($detail->product->payableAccount)
            ) {
                throw new \Exception("Payable account for [{$detail->product->name}] is required.");
            }

            if (
                $detail instanceof HasCreditableWithholdingTax &&
                $detail->withholding_tax_rate > 0 && empty($detail->withholdingTaxAccount)
            ) {
                throw new NoTaxException("Creditable witholding tax account of [{$detail->product->name}] is required.");
            }

            if (
                $detail instanceof HasCreditableTax &&
                $detail->purchase_tax_rate > 0 && empty($detail->purchastTaxAccount)
            ) {
                throw new NoTaxException("Purchase tax account of [{$detail->product->name}] is required.");
            }

            if (
                $detail instanceof CanWithholdTax &&
                $detail->withholding_tax_rate > 0 && empty($detail->withholdingTaxAccount)
            ) {
                throw new NoTaxException("Witholding tax account of [{$detail->product->name}] is required.");
            }

            if (
                $detail instanceof CanCollectSalesTax &&
                $detail->sales_tax_rate > 0 && empty($detail->salesTaxAccount)
            ) {
                throw new NoTaxException("Sales tax account of [{$detail->product->name}] is required.");
            }

            if ($detail instanceof HasPayable && empty($detail->product->payableAccount)) {
                throw new \Exception("Payable account for [{$detail->product->name}] is required.");
            }

            if ($detail instanceof Journalable && $detail->debit > 0 && $detail->credit > 0) {
                throw new NoTaxException("The account [{$detail->chartAccount->name}] cannot have both debit and credit amounts.");
            }
        }

        foreach ($transaction->details()->get() as $detail) {
            foreach ($detail->debitRaw() as $entry) {
                if (!$entry instanceof JournalEntry) {
                    throw new \Exception('Journal row record  is not instanciable.');
                }
            }

            foreach ($detail->creditRaw() as $entry) {
                if (!$entry instanceof JournalEntry) {
                    throw new \Exception('Journal row record  is not instanciable.');
                }
            }
        }

        $totalDebits = 0;
        $totalCredits = 0;
        foreach ((new Debit($transaction))->make()->all() as $row) {
            $totalDebits += $row['debit'];
        }

        foreach ((new Credit($transaction))->make()->all() as $row) {
            $totalCredits += $row['credit'];
        }

        if ($totalDebits <= 0 && $totalCredits <= 0) {
            throw new \Exception('There is no debit and credit amount.');
        }

        if ($totalDebits <= 0) {
            throw new \Exception('Total debit amount is not balance vs total credit.');
        }

        if ($totalCredits <= 0) {
            throw new \Exception('Total credit amount is not balance vs total debit.');
        }

        if ($totalCredits != $totalDebits) {
            throw new \Exception("Journal entries total debits [{$totalDebits}] and credits [{$totalCredits}] are not balance.");
        }
    }
}