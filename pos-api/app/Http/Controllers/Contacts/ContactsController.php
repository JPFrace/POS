<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contacts\Contacts\ContactStoreRequest;
use App\Http\Resources\Contact\ContactResource;
use App\Models\Contact;
use App\Repositories\ContactRepository;
use Illuminate\Http\Request;

class ContactsController extends Controller
{

    public function __construct(protected ContactRepository $repository)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->catch(
            fn(): mixed =>
            $this->query($this->repository, ContactResource::class, $request),
            expectResponse: true
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactStoreRequest $contact)
    {
        $this->catch(fn(): mixed => $this->repository->create($contact->only([
            'id_no',
            'sub_type_id',
            'class_id',
            'first_name',
            'last_name',
            'middle_name',
            'name',
            'suffix',
            'email',
            'billing_address',
            'country_id',
            'zip_code',
            'contact_number',
            'tax_id',
            'contacts'
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
    public function update(Request $request, Contact $contact)
    {
        $this->catch(fn(): mixed => $this->repository->update($request->only([
            'sub_type_id',
            'class_id',
            'type',
            'first_name',
            'last_name',
            'middle_name',
            'name',
            'suffix',
            'email',
            'billing_address',
            'country_id',
            'zip_code',
            'contact_number',
            'file',
            'tax_id',
            'contacts'
        ]), $contact->uuid), expectResponse: false);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $this->catch(fn(): mixed => $this->repository->delete($contact->uuid, 'uuid'), expectResponse: false);
    }
}
