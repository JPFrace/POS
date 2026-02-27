<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Http\Requests\Setup\Reports\StoreReportRequest;
use App\Http\Requests\Setup\Reports\UpdateReportRequest;
use App\Http\Resources\Setup\ReportResource;
use App\Models\Report;
use App\Repositories\ReportsRepository;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function __construct(protected ReportsRepository $repository)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Setup.Setup Reports", "List");

        return $this->query($this->repository, ReportResource::class, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request)
    {
        $request->user()->throwCannot("Setup.Setup Reports", "Create");
        return $this->catch(fn(): mixed => $this->repository->create($request->only([
            'name',
            'description',
            'created_by',
            'is_inactive',
        ])), expectResponse: false);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportRequest $request, Report $report)
    {
        $request->user()->throwCannot("Setup.Setup Reports", "Edit");
        return $this->catch(fn(): mixed => $this->repository->update($request->only([
            'name',
            'description',
            'is_inactive',
        ]), $report->uuid, 'uuid'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report, Request $request)
    {
        $request->user()->throwCannot("Setup.Setup Reports", "Delete");

        $this->repository->delete($report->uuid, 'uuid');
    }
}
