<?php

namespace App\Concerns\Transactions;

use App\Concerns\Exceptions;
use App\Contracts\Supports\Repository;
use App\Events\Transactions\PostableTransactionsCreated;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Creatable implements \App\Contracts\Transactions\Creatable
{
    use Exceptions;

    public function __construct(protected User $user, protected Repository $repository)
    {

    }

    public function create(array $data = []): Model|null
    {
        // Should never create when the calendar is locked.
        $this->calendarLock(Carbon::parse($data['date']));

        $transaction = $this->repository->create($data);

        event(new PostableTransactionsCreated($this->user, $transaction));

        return $transaction;
    }
}