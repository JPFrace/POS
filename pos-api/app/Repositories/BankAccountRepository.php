<?php

namespace App\Repositories;

use App\Models\BankAccount;

class BankAccountRepository extends Repository
{
    use Conditions\BankAccountsConditions;
    public function __construct(protected BankAccount $model)
    {

    }
}
