<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactDetail;
use App\Repositories\VendorContactsRepository;
use Illuminate\Http\Request;

class VendorContactsController extends Controller
{
    public function __construct(protected VendorContactsRepository $repository)
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
    public function destroy(Contact $vendor, ContactDetail $contact)
    {
        $this->catch(fn(): mixed => $this->repository->delete($contact->uuid, 'uuid'), false);
    }
}
