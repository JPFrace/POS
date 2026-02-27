<?php

namespace App\Concerns\Posts;

use App\Concerns\Exceptions;
use App\Concerns\Journal;
use App\Contracts\Business\CanPost;
use App\Contracts\Business\Transactable;
use App\Contracts\Supports\Handleable;
use App\Models\User;

class Unpostable implements Handleable
{
    use Exceptions;

    public function __construct(protected User $user, protected Transactable|CanPost $transaction)
    {

    }

    public function handle(array $attributes = [])
    {
        $transaction = $this->transaction;

        // Should never unpost when the calendar is locked.
        $this->calendarLock($transaction);

        $date = now();
        $transaction->unpost($this->user, $date);

        Journal::deletable($transaction)->handle();
    }
}