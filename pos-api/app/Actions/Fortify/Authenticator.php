<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Fortify;
use Laravel\Fortify\LoginRateLimiter;

use Auth;

class Authenticator extends InheritAttemptToAuthenticate
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        $auths = [
            AdminAuthenticate::class,
        ];

        foreach ($auths as $auth) {
            if ((new $auth($this->getLimiter()))->handle($request, $next)) {
                return $next($request);
            }
        }

        $this->throwFailedAuthenticationException($request);
    }
}
