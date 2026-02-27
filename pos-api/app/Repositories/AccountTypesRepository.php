<?php
namespace App\Repositories;

use App\Models\AccountType;

class AccountTypesRepository extends Repository
{
    use Conditions\AccountClassConditions;

    public function __construct(protected AccountType $model)
    {

    }
}
