<?php

namespace App\Listeners\Business\Journals;

use App\Events\Journals\JournalDeleted;
use App\Services\Reports\BeginningEndingBalance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RunBalance
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
    public function handle(JournalDeleted $event): void
    {
        BeginningEndingBalance::generate(
            $event->chartAccount,
            $event->start->startOfDay(),
            $event->end->endOfDay()
        )->runtime();
    }
}
