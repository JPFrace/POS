<?php

namespace App\Models;

use App\Contracts\Journals\Entryable;
use App\Enums\TransType;
use App\Models\Concerns\Deposits\CreateableJournal;
use App\Models\Concerns\Deposits\RevertableJournal;
use App\Supports\Dto\Journals\JournalEntry;
use App\Supports\Dto\Journals\JournalEntryDetail;
use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use App\Supports\Utils\Amount;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepositDetail extends Relations\DepositDetail implements Entryable
{
    use HasFactory;
    use SoftDeletes;


    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }
    protected $fillable = [
        'deposit_id',
        'date',
        'contact_idno',
        'payment_method_id',
        'memo',
        'ref_no',
        'rate',
        'transactable_type',
        'transactable_id'
    ];

    /**
     * Get journal debity entry raw data
     * @return array
     */
    public function debitRaw(): array
    {
        $parent = $this->parent;

        $data = [
            new JournalEntry(
                $parent->cashInBank,
                new Amount($parent->totalDeposit()),
                new JournalEntryDetail(
                    TransType::DEPOSIT,
                    $parent->ref_no,
                    $parent->ref_no,
                    $parent->creator,
                    $parent->date,
                    null,
                    null,
                    null,
                    $parent->remarks,
                    null,
                )
            )
        ];

        return $data;
    }

    /**
     * Get journal credit entry raw data
     * @return array
     */
    public function creditRaw(): array
    {
        $parent = $this->parent;
        $cashInBank = $this->transactable->cashInBank;

        $data = [
            new JournalEntry(
                $cashInBank,
                new Amount($parent->totalTransactable($cashInBank)),
                new JournalEntryDetail(
                    TransType::DEPOSIT,
                    $parent->ref_no,
                    $parent->ref_no,
                    $parent->creator,
                    $parent->date,
                    null,
                    null,
                    null,
                    $this->memo,
                    null,
                )
            ),
        ];

        return $data;
    }

}
