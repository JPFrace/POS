<?php

namespace App\Core;

use App\Concerns\Posts;
use App\Contracts\Business\CanPost;
use App\Models\User;

class Post
{
    /**
     * Initial
     * @param \App\Models\User $user
     */
    public function __construct(protected User $user)
    {

    }

    /**
     * Post a transaction
     * @param \App\Contracts\Business\CanPost $transaction
     */
    public function post(CanPost $transaction)
    {
        return Posts::post($this->user, $transaction)->handle();
    }

    /**
     * Unpost a transaction
     * @param \App\Contracts\Business\CanPost $transaction
     */
    public function unpost(CanPost $transaction)
    {
        return Posts::unpost($this->user, $transaction)->handle();
    }
}