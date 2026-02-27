<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\Calendar\StoreCalendarRequest;
use App\Http\Requests\Accounting\Calendar\UpdateCalendarRequest;
use App\Http\Resources\Accounting\CalendarResource;
use App\Models\Calendar;
use App\Repositories\CalendarRepository;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function __construct(protected CalendarRepository $repository) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Accounting.Calendars", "List");

        return $this->query($this->repository, CalendarResource::class, $request);
    }

    public function getCalendars(Request $request)
    {
        $request->user()->throwCannot("Accounting.Calendars", "List");

        return Calendar::select(['uuid', 'year', 'start_date', 'end_date'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCalendarRequest $request)
    {
        $this->repository->create($request->all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCalendarRequest $request, Calendar $calendar)
    {
        $this->repository->update($request->all(), $calendar->uuid);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Calendar $calendar, Request $request)
    {
        $request->user()->throwCannot("Accounting.Calendars", "Delete");

        abort_if(
            $calendar->hasConstraints(),
            422,
            'Deletion not allowed. This record is currently in use.'
        );

        $this->repository->delete($calendar->uuid);
    }
}
