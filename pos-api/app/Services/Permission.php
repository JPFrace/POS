<?php
namespace App\Services;

use App\Models\Policy;
use App\Models\User;

class Permission
{
    public static function can(User $user, string $module, string|array $action): bool
    {
        // Check if user, module, or action is null or empty
        if (empty($module) || empty($action)) {
            return false;
        }

        $modules = explode('.', $module);

        // Retrieve the policy and action from the database
        $policy = Policy::where('name', $modules[0])->first();
        if (!$policy)
            return false;

        // Check for sub-module if exists
        if (isset($modules[1])) {
            $policy = $policy->children()->where('name', $modules[1])->first();
            if (!$policy)
                return false;
        }


        $action = is_string($action) ? [$action] : $action;
        // Find the action within the policy
        $action = $policy?->actions()->whereIn('name', $action)->pluck('id');
        if (!$action)
            return false;

        $permissions = $user->where('id', $user->id)->whereHas("roles", function ($query) use ($action) {
            $query->whereRelation('role', function ($query) use ($action) {
                $query->whereRelation('permissions', function ($query) use ($action) {
                    $query->whereIn('action_id', $action);
                });
            });
        })->get();

        return $permissions->isNotEmpty();
    }
}