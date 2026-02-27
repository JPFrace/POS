<?php
namespace App\Repositories;

use App\Models\OfficialReceiptStatus;

class OfficialReceiptStatusRepository extends Repository
{
    use Conditions\OfficialReceiptStatusConditions;

    public function __construct(protected OfficialReceiptStatus $model)
    {

    }
}