<?php

namespace App\Repositories;

use App\Models\PaymentDetail;

class PaymentDetailsRepository extends Repository
{
    public function __construct(protected PaymentDetail $model)
    {

    }
}