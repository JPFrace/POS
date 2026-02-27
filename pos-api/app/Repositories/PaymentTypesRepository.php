<?php

namespace App\Repositories;

use App\Models\PaymentType;
use Illuminate\Support\Facades\Auth;

class PaymentTypesRepository extends Repository
{
    use Conditions\PaymentTypesConditions;

    public function __construct(protected PaymentType $model)
    {

    }

    public function create(array $data): PaymentType
    {
        $data['created_by'] = Auth::user()->id;
        return $this->model()->create($data);
    }
}