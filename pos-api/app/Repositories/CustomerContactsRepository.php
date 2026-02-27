<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Models\ContactDetail;

class CustomerContactsRepository extends Repository
{
    public function __construct(protected ContactDetail $model)
    {
    }
}