<?php

namespace App\Http\Controllers\Contacts;

use App\Enums\ContactType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contacts\Contacts\ContactStoreRequest;
use App\Http\Requests\Contacts\Contacts\ContactUpdateRequest;
use App\Http\Resources\Contact\ContactResource;
use App\Models\Contact;
use App\Repositories\CustomersRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function __construct(protected CustomersRepository $repository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Contacts.Customers", "List");

        $query = gettype($request->get('query', [])) == 'string' ? json_decode($request->get('query', []), true) : $request->get('query', []);
        $request->merge([
            'query' => [
                ...$query,
                'customer_only' => true,
            ]
        ]);
        return $this->query($this->repository, ContactResource::class, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactStoreRequest $request)
    {
        return $this->catch(fn(): mixed => $this->repository->create($request->only([
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
            'tax_id',
            'contacts',
            'created_by'
        ])), expectResponse: false);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $customer)
    {
        return $this->catch(fn(): mixed => $this->repository->findByUuid($customer->uuid), expectResponse: true);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ContactUpdateRequest $request, Contact $customer)
    {
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
        ]), $customer->uuid));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $customer, Request $request)
    {
        $request->user()->throwCannot("Contacts.Customers", "Delete");

        $this->catch(fn(): mixed => $this->repository->delete($customer->uuid));
    }
}
