<?php

namespace App\Reports\Templates;

use App\Models\Contact;
use App\Enums\ContactType;
use App\Enums\TransType;
use App\Models\Relations\OfficialReceipt;
use Illuminate\Support\Facades\DB;

class CustomerLedger
{
    public function __construct(protected array $dates)
    {
    }
    public static function make(array $dates)
    {
        return (new self($dates))->handle();
    }
    public function handle()
    {
        [$dateFrom, $dateTo] = $this->dates;

        $customerLedger = Contact::query()
            ->where('type', ContactType::CUSTOMER)
            ->orderBy('id_no')
            ->withSum(
                [
                    'journals as balance_forward' => fn($q) => $q
                        ->where('contact_type', ContactType::CUSTOMER)
                        ->where('posted_at', '<', $dateFrom)
                        ->where(
                            fn($q) => $q
                                ->whereIn('trans_type', [TransType::JOURNAL, TransType::BEGINNING])
                                ->orWhere(fn($q) => $q->where('trans_type', TransType::COLLECTION)->where('credit', '>', 0))
                                ->orWhere(fn($q) => $q->where('trans_type', TransType::INVOICE)->where('debit', '>', 0))
                        )
                ],
                DB::raw('debit - credit')
            )
            ->with([
                'journals' => fn($query) => $query
                    ->where('contact_type', ContactType::CUSTOMER)
                    ->whereIn('trans_type', [
                        TransType::JOURNAL,
                        TransType::COLLECTION,
                        TransType::INVOICE,
                        TransType::BEGINNING,
                    ])
                    ->whereBetween('posted_at', [$dateFrom, $dateTo])
                    ->with([
                        'financialCode:trans_type,code',
                        'transactable' => fn($morphTo) => $morphTo->constrain([
                            OfficialReceipt::class => fn($query) => $query->with([
                                'denominations:or_id,reference_no,payment_method_id',
                                'denominations.payment_method:id,name',
                            ])
                        ])
                    ])
                    ->orderBy('posted_at'),
            ])
            ->get()

            ->flatMap(function ($contact) use ($dateFrom) {
                $balanceForward = (float) ($contact->balance_forward ?? 0);
                $balance = $balanceForward;

                $balanceFwdRow = $balanceForward !== 0.0 ? [
                    [
                        'customer_id' => $contact->id_no,
                        'customer_name' => $contact->full_name,
                        'date' => $dateFrom,
                        'debit' => null,
                        'credit' => null,
                        'balance' => $balanceForward,
                        'code' => null,
                        'trans_no' => 'Balance Fwd',
                    ]
                ] : [];

                if ($contact->journals->isEmpty()) {
                    return $balanceFwdRow ?: [
                        [
                            'customer_id' => $contact->id_no,
                            'customer_name' => $contact->full_name,
                            'date' => null,
                            'debit' => null,
                            'credit' => null,
                            'balance' => null,
                            'code' => null,
                            'trans_no' => 'No Activity',
                        ]
                    ];
                }

                $rows = $contact->journals
                    ->filter(
                        fn($journal) => match ($journal->trans_type) {
                            TransType::COLLECTION => $journal->credit > 0,
                            TransType::INVOICE => $journal->debit > 0,
                            default => true,
                        }
                    )
                    ->groupBy(
                        fn($journal) => match ($journal->trans_type) {
                            TransType::COLLECTION => "collection_{$journal->transactable_id}",
                            TransType::INVOICE => "invoice_{$journal->transactable_id}",
                            TransType::BEGINNING => "beginning_{$journal->transactable_id}",
                            TransType::JOURNAL => "journal_{$journal->transactable_id}",
                            default => "other_{$journal->id}",
                        }
                    )
                    ->map(function ($group) use ($contact, &$balance) {
                        $journal = $group->first();
                        $debit = $group->sum('debit');
                        $credit = $group->sum('credit');
                        $balance += $debit - $credit;

                        return [
                            'customer_id' => $contact->id_no,
                            'customer_name' => $contact->full_name,
                            'date' => $journal->posted_at,
                            'debit' => $debit ?: null,
                            'credit' => $credit ?: null,
                            'balance' => $balance,
                            'code' => $journal->financialCode?->code,
                            'trans_no' => match ($journal->trans_type) {
                                TransType::JOURNAL,
                                TransType::BEGINNING,
                                TransType::INVOICE => $journal->ref_no,
                                TransType::COLLECTION => $journal->transactable?->references,
                                default => null,
                            },
                        ];
                    });

                return collect($balanceFwdRow)->concat($rows);
            });

        return [
            'customer_ledger' => $customerLedger,
            'total' => [
                'debits' => $customerLedger->sum('debit'),
                'credits' => $customerLedger->sum('credit'),
                'balance' => $customerLedger
                    ->groupBy('customer_id')
                    ->map(fn($rows) => $rows->sortBy('date')->last()['balance'] ?? 0)
                    ->sum(),
            ],
        ];
    }
}