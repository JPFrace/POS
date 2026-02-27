<?php

namespace App\Repositories\Conditions;

use App\Enums\ContactType;

trait VendorConditions
{
    public function contactsCondition(&$builder, $query)
    {
        return $builder->when($query['contacts'], function ($builder) use ($query) {
            $builder->where('type', ContactType::VENDOR)
                ->with(['contacts:contact_id,uuid,name,billing_address,contact_number']);
        });
    }
}