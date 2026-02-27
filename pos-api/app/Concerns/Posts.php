<?php

namespace App\Concerns;

use App\Contracts\Business\CanPost;
use App\Contracts\Journals\Postable;
use App\Contracts\Supports\Handleable;
use App\Models\User;

class Posts implements Postable
{
    /**
     * @inheritDoc
     */
    public static function post(User $user, CanPost $transaction): Handleable
    {
        return new Posts\Postable($user, $transaction);
    }

    /**
     * @inheritDoc
     */
    public static function unpost(User $user, CanPost $transaction): Handleable
    {
        return (new Posts\Unpostable($user, $transaction));
    }
}