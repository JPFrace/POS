<?php

namespace App\Repositories;

use App\Models\BankAccount;

class BankAccountsRepository extends Repository{

    public function __construct(protected BankAccount $model){

    }
}