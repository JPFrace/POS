<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactDetail;
use App\Repositories\CustomerContactsRepository;
use Illuminate\Http\Request;

class CustomerContactsController extends Controller
{
    public function __construct(protected CustomerContactsRepository $repository)
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
    public function destroy(Contact $customer, ContactDetail $contact)
    {
        $this->catch(fn(): mixed => $this->repository->delete($contact->uuid), false);
    }
}
