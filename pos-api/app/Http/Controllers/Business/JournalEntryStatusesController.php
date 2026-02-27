<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\JournalEntryStatus;
use Illuminate\Http\Request;
use App\Http\Resources\Business\JournalEntryStatusResource;
use App\Repositories\JournalEntryStatusRepository;

class JournalEntryStatusesController extends Controller
{
    public function __construct(protected JournalEntryStatusRepository $repository)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->query($this->repository, JournalEntryStatusResource::class, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(JournalEntryStatus $journalEntryStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JournalEntryStatus $journalEntryStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JournalEntryStatus $journalEntryStatus)
    {
        //
    }
}
