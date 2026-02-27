<?php
namespace App\Repositories;

use App\Models\AccountClass;

class AccountClassRepository extends Repository
{
    use Conditions\AccountClassConditions;

    public function __construct(protected AccountClass $model)
    {

    }
}
