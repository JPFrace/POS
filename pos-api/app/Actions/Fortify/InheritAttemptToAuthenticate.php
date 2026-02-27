<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\LoginRateLimiter;

class InheritAttemptToAuthenticate extends \Laravel\Fortify\Actions\AttemptToAuthenticate
{

    public function getLimiter(): LoginRateLimiter
    {
        return $this->limiter;
    }
}
