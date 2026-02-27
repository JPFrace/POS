<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\OfficialReceiptStatus;
use Illuminate\Http\Request;
use App\Http\Resources\Business\OfficialReceiptStatusResource;
use App\Repositories\OfficialReceiptStatusRepository;

class OfficialReceiptStatusesController extends Controller
{
    public function __construct(protected OfficialReceiptStatusRepository $repository)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->query($this->repository, OfficialReceiptStatusResource::class, $request);
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
    public function show(OfficialReceiptStatus $officialReceiptStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OfficialReceiptStatus $officialReceiptStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfficialReceiptStatus $officialReceiptStatus)
    {
        //
    }
}
