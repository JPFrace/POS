<?php

namespace App\Repositories;

use App\Models\Role;

class RolesRepository extends Repository
{
    use Conditions\RoleConditions;

    public function __construct(protected Role $model)
    {

    }
}