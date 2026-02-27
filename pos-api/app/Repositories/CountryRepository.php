<?php
namespace App\Repositories;

use App\Models\Country;

class CountryRepository extends Repository
{
    use Conditions\CountryConditions;

    public function __construct(protected Country $model)
    {

    }
}