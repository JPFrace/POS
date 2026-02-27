<?php

namespace App\Http\Controllers\Security;

use App\Models\Role;
use App\Supports\Cache\Key;
use Illuminate\Http\Request;
use App\Supports\Cache\Module;
use App\Http\Controllers\Controller;
use App\Http\Requests\Security\Roles\RoleRequest;
use App\Repositories\RolesRepository;
use App\Http\Resources\Security\RoleResource;
use App\Http\Requests\Security\Roles\RoleStoreRequest;
use App\Http\Requests\Security\Roles\RoleUpdateRequest;

class RolesController extends Controller
{
    public function __construct(protected RolesRepository $repository)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $request->user()->throwCannot("Security.Roles", "List");

        return $this->query($this->repository, RoleResource::class, $request);
    }

    public function getPermissions(Role $role, Request $request)
    {
        return $this->repository->list(
            query: [
                'uuid' => $role->uuid,
                ...$request->get('query', [])
            ],
            get: true
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $request->user()->throwCannot("Security.Roles", "Create");
        
        return $this->catch(fn() => $this->repository->create($request->only([
            'slug',
            'name',
            'description',
            'is_inactive'
        ])), true);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role)
    {
        $request->user()->throwCannot("Security.Roles", "Edit");

        return $this->catch(fn() => $this->repository->update($request->only([
            'slug',
            'name',
            'description',
            'is_inactive'
        ]), $role->uuid), true);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Role $role)
    {
        $request->user()->throwCannot("Security.Roles", "Delete");

        abort_if(
            $role->hasConstraints(),
            422,
            'Deletion not allowed. This role is currently in use.'
        );

        return $this->catch(fn() => $this->repository->delete($role->uuid, 'uuid'), true);
    }
}

