<?php

namespace App\Concerns;

use App\Contracts\Business\CanPost;
use App\Contracts\Business\Transactable;
use App\Models\Calendar;
use Carbon\Carbon;


trait Exceptions
{
    public function calendarLock(Transactable|Carbon $transaction)
    {
        $date = $transaction;
        if ($transaction instanceof Transactable) {
            $date = $transaction->getDate();
        }

        if (Calendar::isClosed($date)) {
            throw new Exceptions\CalendarClosedException();
        }
    }

    public function postingLock(CanPost $transaction)
    {
        if ($transaction->isPosted()) {
            throw new Exceptions\PostedException();
        }
    }

    public function unpostingLock(CanPost $transaction)
    {
        if (!$transaction->isPosted()) {
            throw new Exceptions\UnPostedException();
        }
    }
}