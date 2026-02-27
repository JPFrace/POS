<?php

namespace App\Concerns\Transactions;

use App\Concerns\Exceptions;
use App\Concerns\Journal;
use App\Contracts\Supports\Repository;
use App\Events\Transactions\PostableTransactionsDeleted;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Deletable implements \App\Contracts\Transactions\Deletable
{
    use Exceptions;

    public function __construct(protected User $user, protected Repository $repository)
    {

    }

    public function delete(mixed $id, string $key = 'uuid'): Model|null
    {
        $transaction = $this->repository->findByUuid($id, $key);

        // Should never delete when the calendar is locked.
        $this->calendarLock($transaction);

        // Delete existing journal entries
        Journal::deletable($transaction);

        $this->repository->delete($id, $key);

        event(new PostableTransactionsDeleted($this->user, $transaction));

        return $transaction;
    }
}