<?php

namespace App\Repositories;
use App\Models\Action;
use App\Models\Permission;
use App\Models\Policy;
use App\Models\Role;


class PolicyRepository extends Repository
{
    use Conditions\PolicyConditions;

    public function __construct(protected Policy $model)
    {

    }

    /**
     * Set or unset permission
     * @param \App\Models\Role $role
     * @param \App\Models\Action $action
     * @param bool $checked
     * @return mixed
     */
    public function set(Role $role, Action $action, bool $checked): mixed
    {
        $model = $role->permissions()->where("action_id", $action->id);
        if (!$checked) {
            return $model->delete();
        }

        // Ensure that the permission won't re-inserted
        if ($model->first()) {
            return false;
        }

        return $role->permissions()->save(new Permission([
            'action_id' => $action->id
        ]));
    }
}