<?php

namespace App\Contracts\Journals;

use App\Contracts\Business\CanPost;
use App\Contracts\Supports\Handleable;
use App\Models\User;

interface Postable
{
    /**
     * Post a transaction
     * @param \App\Models\User $user
     * @param \App\Contracts\Business\CanPost $transaction
     * @return \App\Contracts\Supports\Handleable
     */
    public static function post(User $user, CanPost $transaction): Handleable;

    /**
     * UnPost a transaction
     * @param \App\Models\User $user
     * @param \App\Contracts\Business\CanPost $transaction
     * @return \App\Contracts\Supports\Handleable
     */
    public static function unpost(User $user, CanPost $transaction): Handleable;

}