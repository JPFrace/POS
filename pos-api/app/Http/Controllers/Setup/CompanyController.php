<?php

namespace App\Http\Controllers\setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setup\Companies\StoreCompanyRequest;
use App\Http\Resources\Setup\CompanyResource;
use App\Models\Company;
use App\Repositories\CompanyRepository;
use App\Supports\Utils\Upload;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
class CompanyController extends Controller
{
    use Upload;
    public function __construct(protected CompanyRepository $repository)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->query($this->repository, CompanyResource::class, $request)[0];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        $request->merge(['created_by' => auth()->id()]);
        $this->repository->create($request->all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCompanyRequest $request, Company $company)
    {
        $request->user()->throwCannot("Setup.Company", "Edit");
        $this->repository->update($request->only([
            'name',
            'tin_no',
            'address',
            'phone',
            'email',
            'file'
        ]), $company->uuid);
    }

    public function show()
    {
        return $this->repository->getFirst();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        auth()->user()->canDelete("Company");
        $this->repository->delete($company->uuid);
    }
}
