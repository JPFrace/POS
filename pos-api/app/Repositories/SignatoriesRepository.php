<?php   

namespace App\Repositories;

use App\Models\Signatories;

class SignatoriesRepository extends Repository
{

     use Conditions\SignatoriesConditions;
    public function __construct(protected Signatories $model)
    {

    }
}
