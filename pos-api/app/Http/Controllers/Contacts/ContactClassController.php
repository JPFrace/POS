<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Contact\ContactClassResource;
use App\Repositories\ContactClassRepository;

class ContactClassController extends Controller
{

    public function __construct(protected ContactClassRepository $repository)
    {

    }

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        return $this->query($this->repository, ContactClassResource::class, $request);
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
