<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setup\ReportSignatory\StoreReportSignatoryRequest;
use App\Http\Resources\Setup\ReportSignatoryResource;
use App\Models\ReportSignatory;
use App\Repositories\ReportSignatoryRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class ReportSignatoryController extends Controller
{
    public function __construct(protected ReportSignatoryRepository $repository)
    {
    }
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $request->user()->throwCannot("Setup.Report Signatories", "List");

        return $this->query($this->repository, ReportSignatoryResource::class, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportSignatoryRequest $request)
    {
        return $this->catch(fn(): mixed => $this->repository->create($request->only([
            'report_id',
            'label',
            'signatory_id',
            'created_by',
            'is_inactive',
            'sort',
            'year_signatory',
        ])), expectResponse: false);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreReportSignatoryRequest $request, ReportSignatory $report_signatory)
    {
        return $this->catch(fn(): mixed => $this->repository->update($request->only([
            'report_id',
            'label',
            'signatory_id',
            'created_by',
            'is_inactive',
            'sort',
            'year_signatory',
        ]), $report_signatory->uuid));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReportSignatory $report_signatory, Request $request)
    {
        $request->user()->throwCannot("Setup.Report Signatories", "Delete");
        try {
            $report_signatory->delete();
        } catch (QueryException $e) {
            if ($e->getCode() == '23000') {
                return response()->json([
                    'message' => 'Cannot delete Report Signatory: ' . $report_signatory->code . '\n' . $report_signatory->description . '. It is referenced by other records.'
                ], 409);
            }
            throw $e;
        }
    }
}
