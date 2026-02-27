<?php

namespace App\Repositories\Concerns\Security;

use App\Models\Role;
use App\Models\User;
use DB;

trait UserUpdate
{
    /**
     * Create user and assign roles
     * @param array $data
     * @return \App\Models\User
     */
    public function update(array $data, $id, $key = 'id'): User
    {
        $roles = $data['roles'];

        unset($data['roles']);

        return DB::transaction(function () use ($data, $roles, $id, $key) {
            $user = parent::update($data, $id, $key);
            $user->roles()->delete();

            foreach (Role::whereIn('uuid', $roles)->get() as $role) {
                $user->roles()->create([
                    'role_id' => $role->id,
                ]);
            }

            return $user;
        });
    }
}