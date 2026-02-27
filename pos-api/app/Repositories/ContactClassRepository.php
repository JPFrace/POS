<?php
namespace App\Repositories;

use App\Models\ContactClass;

class ContactClassRepository extends Repository
{
    use Conditions\ContactClassConditions;

    public function __construct(protected ContactClass $model)
    {

    }
}