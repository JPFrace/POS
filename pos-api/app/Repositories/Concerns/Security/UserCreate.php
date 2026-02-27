<?php

namespace App\Repositories\Concerns\Security;

use App\Models\Role;
use App\Models\User;
use DB;

trait UserCreate
{
    /**
     * Create user and assign roles
     * @param array $data
     * @return \App\Models\User
     */
    public function create(array $data): User
    {
        $roles = $data['roles'];

        unset($data['roles']);

        return DB::transaction(function () use ($data, $roles) {
            $user = parent::create($data);

            $user->roles()->sync(Role::whereIn('uuid', $roles)->get());

            return $user;
        });
    }
}