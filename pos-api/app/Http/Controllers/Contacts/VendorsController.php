<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contacts\Contacts\ContactStoreRequest;
use App\Http\Requests\Contacts\Contacts\ContactUpdateRequest;
use App\Http\Resources\Contact\ContactResource;
use App\Models\Contact;
use App\Repositories\VendorRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class VendorsController extends Controller
{
    public function __construct(protected VendorRepository $repository)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Contacts.Vendors", "List");

        $query = gettype($request->get('query', [])) == 'string' ? json_decode($request->get('query', []), true) : $request->get('query', []);
        $request->merge([
            'query' => [
                ...$query,
                'vendor_only' => true,
            ]
        ]);
        return $this->catch(fn(): mixed => $this->query($this->repository, ContactResource::class, $request), expectResponse: true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactStoreRequest $request)
    {
        $request->user()->throwCannot("Contacts.Vendors", "Create");
        $this->catch(fn(): mixed => $this->repository->create($request->only([
            'id_no',
            'sub_type_id',
            'class_id',
            'name',
            'first_name',
            'last_name',
            'middle_name',
            'suffix',
            'email',
            'billing_address',
            'address',
            'country_id',
            'zip_code',
            'contact_number',
            'contacts',
            'tax_id',
            'created_by'
        ])));
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $vendor)
    {
        return $this->catch(fn(): mixed => $this->repository->findByUuid($vendor->uuid), expectResponse: true);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactUpdateRequest $request, Contact $vendor)
    {
        $request->user()->throwCannot("Contacts.Vendors", "Edit");
        $this->catch(fn(): mixed => $this->repository->update($request->only([
            'sub_type_id',
            'class_id',
            'name',
            'first_name',
            'last_name',
            'middle_name',
            'suffix',
            'email',
            'billing_address',
            'address',
            'country_id',
            'zip_code',
            'contact_number',
            'file',
            'tax_id',
            'contacts'
        ]), $vendor->uuid));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $vendor, Request $request)
    {
        $request->user()->throwCannot("Contacts.Vendors", "Delete");

        $this->catch(fn(): mixed => $this->repository->delete($vendor->uuid, 'uuid'));
    }
}
