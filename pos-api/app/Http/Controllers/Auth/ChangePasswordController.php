<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\ChangePassOnUserRequest;
use App\Http\Requests\Auth\ChangePassOnProfileRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
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
