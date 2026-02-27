<?php
namespace App\Repositories;

use App\Models\PaymentStatus;

class PaymentStatusRepository extends Repository
{
    use Conditions\PaymentStatusConditions;

    public function __construct(protected PaymentStatus $model)
    {

    }
}