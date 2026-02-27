<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\BillStatus;
use Illuminate\Http\Request;
use App\Http\Resources\Business\BillStatusResource;
use App\Repositories\BillStatusRepository;

class BillStatusesController extends Controller
{
    public function __construct(protected BillStatusRepository $repository)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->query($this->repository, BillStatusResource::class, $request);
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
    public function show(BillStatus $billStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BillStatus $billStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BillStatus $billStatus)
    {
        //
    }
}
