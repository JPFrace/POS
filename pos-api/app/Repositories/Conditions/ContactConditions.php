<?php

namespace App\Repositories\Conditions;

use App\Enums\BillStatusEnum;
use App\Enums\ContactType;
use App\Enums\InvoiceStatusEnum;
use App\Enums\PoStatus;
use DB;

trait ContactConditions
{
    public function createdByCondition(&$builder, $query)
    {
        return $builder->when($query['createdBy'], function ($builder) use ($query) {
            $builder->with(['createdBy:id,name']);
        });
    }

    public function contactsCondition(&$builder, $query)
    {
        return $builder->when($query['contacts'], function ($builder) use ($query) {
            $builder->with(['contacts:contact_id,uuid,name,billing_address,contact_number']);
        });
    }

    public function typeCondition(&$builder, $query)
    {
        return $builder->when($query['type'], function ($builder) use ($query) {
            $builder->with(['subType']);
        });
    }

    public function taxCondition(&$builder, $query)
    {
        return $builder->when($query['tax'], function ($builder) use ($query) {
            $builder->with(['tax']);
        });
    }

    public function countryCondition(&$builder, $query)
    {
        return $builder->when($query['country'], function ($builder) use ($query) {
            $builder->with(['country']);
        });
    }

    public function classCondition(&$builder, $query)
    {
        return $builder->when($query['class'], function ($builder) use ($query) {
            $builder->with(['class']);
        });
    }

    public function vendorOnlyCondition(&$builder, $query)
    {
        return $builder->when(isset($query['vendor_only']) && $query['vendor_only'], function ($builder) use ($query) {
            $builder->where('type', ContactType::VENDOR);
        });
    }

    public function customerOnlyCondition(&$builder, $query)
    {
        return $builder->when(isset($query['customer_only']) && $query['customer_only'], function ($builder) use ($query) {
            $builder->where('type', ContactType::CUSTOMER);
        });
    }

    public function firstNameCondition(&$builder, $query)
    {
        return $builder->when(isset($query['first_name']) && $query['first_name'], function ($builder) use ($query) {
            $builder->where('first_name', 'like', '%' . $query['first_name'] . '%');
        });
    }

    public function lastNameCondition(&$builder, $query)
    {
        return $builder->when(isset($query['last_name']) && $query['last_name'], function ($builder) use ($query) {
            $builder->where('last_name', 'like', '%' . $query['last_name'] . '%');
        });
    }

    public function middleNameCondition(&$builder, $query)
    {
        return $builder->when(isset($query['middle_name']) && $query['middle_name'], function ($builder) use ($query) {
            $builder->where('middle_name', 'like', '%' . $query['middle_name'] . '%');
        });
    }

    public function nameCondition(&$builder, $query)
    {
        return $builder->when(isset($query['name']) && $query['name'], function ($builder) use ($query) {
            $builder->where(function ($builder) use ($query) {
                $builder->where('name', 'like', '%' . trim(strtolower($query['name'])) . '%')
                    ->orWhere('first_name', 'like', '%' . trim(strtolower($query['name'])) . '%')
                    ->orWhere('last_name', 'like', '%' . trim(strtolower($query['name'])) . '%');
            });
        });
    }

    public function nameOrIdCondition(&$builder, $query)
    {
        return $builder->when($query['name_or_id'] ?? null, function ($builder, $searchTerm) {
            $searchTerm = strtolower(trim($searchTerm));

            $builder->where(function ($builder) use ($searchTerm) {
                $builder->where('id_no', 'like', "%{$searchTerm}%")
                    ->orWhereRaw(
                        "LOWER(CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name)) LIKE ?",
                        ["%{$searchTerm}%"]
                    )
                    ->orWhereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"]);
            });
        });
    }

    public function unpaidInvoiceCondition(&$builder, $query)
    {
        return $builder->when(isset($query['unpaid_invoice']) && $query['unpaid_invoice'], function ($builder) use ($query) {
            $builder->with([
                'invoices.details' => function ($query) {
                    return $query->with(['product.incomeAccount'])->whereRelation('parent', 'status_id', '<>', InvoiceStatusEnum::PAID);
                }
            ]);
        });
    }

    public function unpaidBillsCondition(&$builder, $query)
    {
        return $builder->when(isset($query['unpaid_bills']) && $query['unpaid_bills'], function ($builder) use ($query) {
            $builder->with([
                'bills.details' => function ($query) {
                    return $query->with(['product.payable'])->whereRelation('parent', 'status_id', '<>', BillStatusEnum::PAID);
                }
            ]);
        });
    }

    public function openOrdersCondition(&$builder, $query)
    {
        return $builder->when(isset($query['open_orders']) && $query['open_orders'], function ($builder) use ($query) {
            $builder->with([
                'orders' => function ($query) {
                    return $query->with([
                        'details' => function ($query) {
                            return $query->select([
                                '*',
                                DB::raw('quantity as original_quantity'),
                                DB::raw('quantity - delivered as balance'),
                            ])->with(['product.expenseAccount']);
                        }
                    ])
                        ->whereHas('details', function ($query) {
                            return $query->whereRaw("quantity - delivered > 0");
                        })->where(function ($query) {
                            return $query->where('status', PoStatus::OPEN)
                                ->orWhere('status', PoStatus::PARTIAL);
                        });
                }
            ]);
        });
    }
}
