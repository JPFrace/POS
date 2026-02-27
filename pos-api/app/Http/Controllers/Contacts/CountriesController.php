<?php

namespace App\Http\Controllers\Contacts;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Resources\Contact\CountryResource;
use App\Repositories\CountryRepository;

class CountriesController extends Controller
{
    public function __construct(protected CountryRepository $repository)
    {

    }

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        return $this->query($this->repository, CountryResource::class, $request);
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
    public function show(Country $country)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Country $country)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        //
    }
}
