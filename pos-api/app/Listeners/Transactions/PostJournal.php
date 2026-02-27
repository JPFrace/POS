<?php

namespace App\Listeners\Transactions;

use App\Contracts\Business\CanPost;
use App\Events\Transactions\PostableTransactionsCreated;
use App\Events\Transactions\PostableTransactionsUpdated;
use App\Core\Post;
use DB;

class PostJournal
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
    public function handle(PostableTransactionsCreated|PostableTransactionsUpdated $event): void
    {
        $transaction = $event->model;

        if (!$transaction instanceof CanPost) {
            throw new \Exception(message: "Transaction instance is not postable.");
        }

        $service = new Post($event->user);

        if (!$this->canAutoPost()) {
            return;
        }

        $service->post($transaction);
    }

    /**
     * Check if the service can auto post
     * @return bool
     */
    public function canAutoPost(): bool
    {
        return (bool) (config('custom.transactions_auto_post')
            ?? DB::table('configs')
                ->where('slug', 'transactions_auto_post')
                ->value('value'));
    }
}
