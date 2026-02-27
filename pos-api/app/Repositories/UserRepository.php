<?php

namespace App\Repositories;

use App\Models\Member;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use App\Repositories\Concerns\Security\UserCreate;
use App\Repositories\Concerns\Security\UserDelete;
use App\Repositories\Concerns\Security\UserUpdate;
use App\Enums\Security\Role as UserRole;
use App\Repositories\Conditions\UserConditions;
use App\Supports\Utils\Upload;
use DB;

class UserRepository extends Repository
{
    use UserCreate, UserUpdate, UserDelete, UserConditions, Upload;

    public function __construct(protected User $model)
    {

    }

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

            $user->roles()->createMany(
                Role::whereIn('uuid', $roles)
                    ->get()
                    ->map(fn($role) => [
                        'role_id' => $role->id,
                    ])
                    ->toArray()
            );

            return $user;
        });
    }

    public function updateProfile($data, $id, $key = 'uuid'): User
    {
        return \DB::transaction(function () use ($data, $id, $key) {
            $file = $data['photo'] ?? null;
            unset($data['photo']);

            $user = parent::update($data, $id, $key);

            if ($file) {
                if ($file = $this->upload($file, 'user')) {
                    $user->file()->associate($file);
                }
            } else {
                $user->file()->delete();
            }

            $user->save();

            return $user;
        });

    }

}