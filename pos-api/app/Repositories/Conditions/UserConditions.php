<?php

namespace App\Repositories\Conditions;

use Illuminate\Database\Eloquent\Builder;

trait UserConditions
{
    public function rolesCondition(Builder &$builder, $query)
    {
        return $builder->when($query['roles'], fn() => $builder->with('roles'));
    }

    public function rolesWithRoleCondition(Builder &$builder, $query)
    {
        return $builder->when($query['roles.role'], fn() => $builder->with('roles.role'));
    }

    public function rolesUuidsCondition(Builder &$builder, $query)
    {
        return $builder->when($query['roles_uuids'], fn() => $builder->whereRelation('roles', function ($builder) use ($query) {
            $builder->whereIn('uuid', $query['roles_uuids']);
        }));
    }
}