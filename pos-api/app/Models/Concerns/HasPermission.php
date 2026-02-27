<?php
namespace App\Models\Concerns;

use Illuminate\Auth\Access\AuthorizationException;

trait HasPermission
{
    public function can($module, $action = [])
    {
        return \App\Services\Permission::can($this, $module, $action);
    }

    public function throwCannot($module, $action)
    {
        $action = is_array($action) ? $action : [$action];

        throw_if(!$this->can($module, $action), AuthorizationException::class, 'Unauthorized Action');
    }
}