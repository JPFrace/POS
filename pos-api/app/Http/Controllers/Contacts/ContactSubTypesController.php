<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Http\Resources\Contact\ContactSubTypeResource;
use App\Repositories\ContactSubTypeRepository;
use Illuminate\Http\Request;

class ContactSubTypesController extends Controller
{
    public function __construct(protected ContactSubTypeRepository $repository) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->query($this->repository, ContactSubTypeResource::class, $request);
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
