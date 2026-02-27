<?php
namespace App\Repositories;

use App\Models\InvoiceStatus;

class InvoiceStatusRepository extends Repository
{
    use Conditions\InvoiceStatusConditions;

    public function __construct(protected InvoiceStatus $model)
    {

    }
}