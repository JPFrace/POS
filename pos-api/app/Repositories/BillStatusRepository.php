<?php
namespace App\Repositories;

use App\Models\BillStatus;

class BillStatusRepository extends Repository
{
    use Conditions\BillStatusConditions;

    public function __construct(protected BillStatus $model)
    {

    }
}