<?php
namespace App\Repositories;


use App\Models\ReportSignatory;
use DB;

class ReportSignatoryRepository extends Repository
{

    use Conditions\ReportSignatoryCondition;

    public function __construct(protected ReportSignatory $model)
    {
    }

}
