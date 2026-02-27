<?php
namespace App\Repositories\Conditions;

trait BankAccountsConditions
{
    public function accountNameCondition(&$builder, $query)
    {
        return $builder->when(isset($query['account_name']) && $query['account_name'], function ($builder) use ($query) {
            $builder->where('account_name', 'like', '%' . $query['account_name'] . '%');
        });
    }

    public function chartAccountCondition(&$builder, $query)
    {
        return $builder->when(isset($query['chartAccount']) && $query['chartAccount'], function ($builder) use ($query) {
            $builder->with('chartAccount');
        });
    }
}
