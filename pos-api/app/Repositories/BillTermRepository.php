<?php
namespace App\Repositories;

use App\Models\BillTerm;

class BillTermRepository extends Repository
{
    public function __construct(protected BillTerm $model)
    {

    }
}
