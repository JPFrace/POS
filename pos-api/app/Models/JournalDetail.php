<?php

namespace App\Models;

use App\Contracts\Business\Journalable;
use App\Contracts\Journals\Entryable;
use App\Enums\ContactType;
use App\Enums\TransType;
use App\Supports\Dto\Journals\JournalEntry;
use App\Supports\Dto\Journals\JournalEntryDetail;
use App\Supports\Utils\Amount;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class JournalDetail extends Relations\JournalDetail implements Journalable, Entryable
{
    use HasFactory;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $fillable = [
        'entry_id',
        'chart_account_id',
        'dept_id',
        'debit',
        'credit',
        'description',
        'contact_type',
        'contact_idno'
    ];

    protected $casts = [
        'debit' => 'decimal:4',
        'credit' => 'decimal:4',
        'posted_at' => 'datetime:m/d/Y',
        'unposted_at' => 'datetime',
        'contact_type' => ContactType::class
    ];

    protected $hidden = [
        'id',
        'dept_id',
        'chart_account_id',
        'entry_id',
        'posted_by',
        'unposted_by'
    ];

    public function debitAmount(): Attribute
    {
        return Attribute::make(
            get: fn() => Amount::acceptable($this->attributes['debit']),
            set: fn($value) => Amount::acceptable($value)
        );
    }

    public function creditAmount(): Attribute
    {
        return Attribute::make(
            get: fn() => Amount::acceptable($this->attributes['credit']),
            set: fn($value) => Amount::acceptable($value)
        );
    }

    /**
     * Get journal debity entry raw data
     * @return array
     */
    public function debitRaw(): array
    {
        $parent = $this->parent;

        if ($this->debit_amount <= 0) {
            return [];
        }

        if ($this->debit_amount > 0 && $this->credit_amount > 0) {
            return [];
        }

        $data = [
            new JournalEntry(
                $this->chartAccount,
                new Amount($this->debit_amount),
                new JournalEntryDetail(
                    TransType::JOURNAL,
                    $parent->je_no,
                    $parent->ref_no,
                    $parent->creator,
                    $parent->date,
                    $this->contact?->full_name,
                    $this->contact_type,
                    $this->contact_idno,
                    $this->description,
                    null,
                    $this->department,
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

        if ($this->credit_amount <= 0) {
            return [];
        }

        if ($this->debit_amount > 0 && $this->credit_amount > 0) {
            return [];
        }

        $data = [
            new JournalEntry(
                $this->chartAccount,
                new Amount($this->credit_amount),
                new JournalEntryDetail(
                    TransType::JOURNAL,
                    $parent->je_no,
                    $parent->ref_no,
                    $parent->creator,
                    $parent->date,
                    $this->contact?->full_name,
                    $this->contact_type,
                    $this->contact_idno,
                    $this->description,
                    null,
                    $this->department,
                )
            )
        ];

        return $data;
    }


}
