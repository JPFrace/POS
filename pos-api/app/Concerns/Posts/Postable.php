<?php

namespace App\Concerns\Posts;

use App\Concerns\Exceptions;
use App\Concerns\Journal;
use App\Contracts\Business\CanPost;
use App\Contracts\Business\Transactable;
use App\Contracts\Supports\Handleable;
use App\Models\Taxonomy;
use App\Models\User;

class Postable implements Handleable
{
    use Exceptions;

    public function __construct(protected User $user, protected CanPost|Transactable $transaction)
    {

    }

    public function handle()
    {
        $transaction = $this->transaction;

        // Should never update when the calendar is locked.
        $this->calendarLock($transaction);

        // Should never post transaction when already posted
        $this->postingLock($transaction);

        $date = now();
        $transaction->post($this->user, $date);

        Journal::createable($transaction)->handle();
    }
}