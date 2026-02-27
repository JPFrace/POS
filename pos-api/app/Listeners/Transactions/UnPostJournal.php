<?php

namespace App\Listeners\Transactions;

use App\Contracts\Business\CanPost;
use App\Events\Transactions\PostableTransactionsDeleted;
use DB;

use App\Core\Post;

class UnPostJournal
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PostableTransactionsDeleted $event): void
    {
        $transaction = $event->transaction;

        if (!$transaction instanceof CanPost) {
            throw new \Exception("Transaction instance is not postable.");
        }

        $service = new Post($event->user);

        $service->unpost($transaction);
    }
}
