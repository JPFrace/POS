<?php

namespace App\Http\Controllers\Business;

use App\Applications\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Business\JournalEntry\StoreJournalEntryRequest;
use App\Http\Requests\Business\JournalEntry\UpdateJournalEntryRequest;
use App\Http\Requests\Business\JournalEntry\UpdateJournalEntryStatusRequest;
use App\Http\Resources\Business\JournalEntryResource;
use App\Models\JournalEntry;
use App\Enums\PostingStatus;
use App\Repositories\JournalEntriesRepository;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class JournalEntriesController extends Controller
{
    private Transaction $application;

    public function __construct(protected JournalEntriesRepository $repository)
    {
        $this->application = new Transaction(Auth::user(), $repository);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Business.Journal Entry", "List");

        return $this->query(
            $this->repository,
            JournalEntryResource::class,
            $request
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJournalEntryRequest $request)
    {
        return $this->catch(
            fn(): mixed => $this->application->create(
                $request->only([
                    'je_no',
                    'ref_no',
                    'date',
                    'memo',
                    'status_id',
                    'attachment',
                    'creator_id',
                    'items'
                ])
            ),
            true
        );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJournalEntryRequest $request, JournalEntry $journal_entry)
    {
        $this->authorize('update', $journal_entry);

        return $this->catch(
            fn(): mixed => $this->application->update(
                $journal_entry->uuid,
                $request->only([
                    'je_no',
                    'ref_no',
                    'date',
                    'memo',
                    'status_id',
                    'attachment',
                    'creator_id',
                    'items'
                ]),
            ),
            true
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, JournalEntry $journal_entry)
    {
        $request->user()->throwCannot("Business.Journal Entry", "Delete");

        $this->authorize('delete', $journal_entry);

        $this->application->delete($journal_entry->uuid);
    }

    /**
     * Update status of journal entries.
     */
    public function updateStatus(UpdateJournalEntryStatusRequest $request)
    {
        $status = PostingStatus::tryFrom($request->status);

        $count = $this->application->updateStatus(
            $status,
            $request->uuids,
        );

        return response()->json([
            'message' => "Successfully updated {$count} journal " . str('entry')->plural($count),
            'count' => $count,
        ]);
    }
}
