<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Http\Requests\Security\Users\ChangePassOnProfileRequest;
use App\Http\Requests\Security\Users\ChangePassOnUserRequest;
use App\Http\Requests\Security\Users\UserProfileUpdateRequest;
use App\Http\Requests\Security\Users\UserStoreRequest;
use App\Http\Requests\Security\Users\UserUpdateRequest;
use App\Http\Resources\Security\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function __construct(private UserRepository $user)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Security.Users", "List");

        return $this->query($this->user, UserResource::class, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $request->user()->throwCannot("Security.Users", "Create");
        return $this->catch(fn() => $this->user->create($request->only([
            'name',
            'email',
            'password',
            'roles'
        ])), true);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $user->load(['roles', 'file']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $request->user()->throwCannot("Security.Users", "Edit");

        return $this->catch(fn() => $this->user->update($request->only([
            'name',
            'email',
            'roles'
        ]), $user->id), true);
    }

    public function updateProfile(UserProfileUpdateRequest $request, User $user)
    {
        return $this->catch(fn() => $this->user->updateProfile($request->only([
            'name',
            'address',
            'contacts',
            'photo'
        ]), $user->uuid), true);

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Request $request)
    {
        $request->user()->throwCannot("Security.Users", "Delete");

        return $this->catch(fn() => $this->user->delete($user->uuid), true);
    }

    /**
     * destroySelected
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function destroySelected(Request $request)
    {
        $request->user()->throwCannot("Security.Users", "Delete");

        return $this->catch(fn() => $this->user->delete($request->get('users')), true);
    }

    public function changePasswordOnProfile(ChangePassOnProfileRequest $request)
    {
        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);
    }

    public function changePasswordOnUser(User $user, ChangePassOnUserRequest $request)
    {
        $user->update([
            'password' => Hash::make($request->password),
        ]);
    }
}
