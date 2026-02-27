<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\LoginRateLimiter;
use Laravel\Fortify\Fortify;
use Auth;

class AdminAuthenticate extends \Laravel\Fortify\Actions\AttemptToAuthenticate
{
    /**
     * Create a new controller instance.
     *
     * @param  \Laravel\Fortify\LoginRateLimiter  $limiter
     * @return void
     */
    public function __construct(LoginRateLimiter $limiter)
    {
        parent::__construct(Auth::guard('web'), $limiter);
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        return $this->guard->attempt(
            $request->only(Fortify::username(), 'password'),
            $request->boolean('remember')
        );
    }
}
