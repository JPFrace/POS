<?php

namespace App\Repositories;

use App\Models\ContactSubType;

class ContactSubTypeRepository extends Repository
{
    public function __construct(protected ContactSubType $model)
    {

    }
}