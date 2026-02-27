<?php

namespace App\Concerns\Transactions;

use App\Concerns\Exceptions;
use App\Contracts\Supports\Repository;
use App\Events\Transactions\PostableTransactionsUpdated;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Updatable implements \App\Contracts\Transactions\Updatable
{
    use Exceptions;

    public function __construct(protected User $user, protected Repository $repository)
    {

    }

    public function update(string $uuid, array $data = []): Model|null
    {
        $transaction = $this->repository->findByUuid($uuid);

        // Should never update when the calendar is locked.
        $this->calendarLock($transaction);

        $this->repository->update($data, $uuid);

        $transaction->refresh();

        event(new PostableTransactionsUpdated($this->user, $transaction));

        return $transaction;
    }
}