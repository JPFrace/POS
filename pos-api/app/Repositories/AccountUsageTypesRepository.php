<?php
namespace App\Repositories;

use App\Models\AccountUsageType;

class AccountUsageTypesRepository extends Repository
{
    use Conditions\AccountUsageTypesConditions;

    public function __construct(protected AccountUsageType $model)
    {

    }
}
