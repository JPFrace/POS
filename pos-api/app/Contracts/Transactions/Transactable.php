<?php

namespace App\Contracts\Transactions;

use App\Contracts\Transactions\Creatable;
use App\Contracts\Transactions\Updatable;
use App\Contracts\Supports\Repository;
use App\Models\User;

interface Transactable
{
    /**
     * Post a transaction
     * @param User $user
     * @param \App\Contracts\Supports\Repository $repository
     * @return \App\Contracts\Transactions\Creatable
     */
    public static function create(User $user, Repository $repository): Creatable;

    /**
     * UnPost a transaction
     * @param User $user
     * @param \App\Contracts\Supports\Repository $repository
     * @return \App\Contracts\Transactions\Updatable
     */
    public static function update(User $user, Repository $repository): Updatable;
}