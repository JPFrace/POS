<?php

namespace App\Repositories;

use App\Models\ContactDetail;

class VendorContactsRepository extends Repository
{
    public function __construct(protected ContactDetail $model)
    {

    }
}