<?php

namespace App\Http\Controllers;

use App\Http\Requests\Externals\ExternalStoreRequest;
use App\Repositories\ExternalRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class ExternalController extends Controller
{
    public function __construct(protected ExternalRepository $repository)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExternalStoreRequest $request)
    {
        $this->catch(fn() => $this->repository->create($request->except(['total'])));
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
