<?php

namespace App\Http\Controllers;

use App\Enums\Security\Role;
use App\Models\User;
use App\Repositories\Concerns\Security\UserCreate;
use App\Repositories\Concerns\Security\UserUpdate;
use App\Repositories\GlobalRepository;
use App\Supports\Utils\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    private GlobalRepository $user;

    public function __construct()
    {
        $this->user = new class extends GlobalRepository {
            use UserCreate, UserUpdate;

            public function __construct()
            {
                parent::__construct(new User);
            }
        };
    }

    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {
        $google = Socialite::driver('google')->user();

        $user = $this->user->findBy($google->email, 'email')->first();

        if ($user) {
            $token = $user->createToken('SPA login', ['*'], now()->addMinutes(2));

            return redirect()->away(Url::frontEndUrl("/auth/authorization/redirect?token=" . $token->plainTextToken));
        }

        return redirect()->away(Url::frontEndUrl("/auth/authorization/failed"));

    }

    public function authorization(Request $request)
    {
        if (!$request->user()) {
            return response()->json([], 400);
        }

        // Auto login user
        Auth::guard('web')->loginUsingId($request->user()->id);

        // Remove immediately their access tokens
        $request->user()->tokens()->delete();
    }
}
