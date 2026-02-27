<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Http\Resources\Security\PolicyResource;
use App\Models\Action;
use App\Models\Role;
use App\Repositories\PolicyRepository;
use Illuminate\Http\Request;
class PoliciesController extends Controller
{

    public function __construct(protected PolicyRepository $policies)
    {
        // 
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $request->user()->throwCannot("Security.Policies", "List");

        return $this->query($this->policies, PolicyResource::class, $request);
    }

    /**
     * Get role actions
     * @param \App\Models\Role $role
     * @return void
     */
    public function getRoleActions(Role $role)
    {
        return $role->actions->pluck('action_id');
    }

    /**
     * Set or unset permission
     * @param \App\Models\Role $role
     * @param \App\Models\Action $action
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function set(Role $role, Action $action, Request $request)
    {
        $this->policies->set($role, $action, $request->get('checked'));
    }
}
