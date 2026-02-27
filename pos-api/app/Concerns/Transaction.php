<?php

namespace App\Concerns;

use App\Concerns\Transactions\StatusUpdatable as ConcernStatusUpdatable;
use App\Contracts\Transactions\StatusUpdatable;
use App\Contracts\Transactions\Creatable;
use App\Contracts\Transactions\Deletable;
use App\Contracts\Supports\Repository;
use App\Contracts\Transactions\Updatable;
use App\Contracts\Transactions\Transactable;
use App\Models\User;

class Transaction implements Transactable
{
    /**
     * @inheritDoc
     */
    public static function create(User $user, Repository $repository): Creatable
    {
        return new Transactions\Creatable($user, $repository);
    }

    /**
     * @inheritDoc
     */
    public static function update(User $user, Repository $repository): Updatable
    {
        return new Transactions\Updatable($user, $repository);
    }

    /**
     * @inheritDoc
     */
    public static function delete(User $user, Repository $repository): Deletable
    {
        return new Transactions\Deletable($user, $repository);
    }

    /**
     * @inheritDoc
     */
    public static function updateStatus(User $user, Repository $repository): StatusUpdatable
    {
        return new ConcernStatusUpdatable($user, $repository);
    }
}