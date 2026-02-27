<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Signatories;
use App\Repositories\SignatoriesRepository;
use App\Http\Requests\Setup\Signatories\SignatoryStoreRequest;
use App\Http\Resources\Setup\SignatoriesResource;
use Illuminate\Http\Exceptions\HttpResponseException;

class SignatoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(protected SignatoriesRepository $signatoriesRepository)
    {
    }

    public function index(Request $request)
    {
        $request->user()->throwCannot("Setup.Signatories", "List");

        return $this->catch(
            fn(): mixed =>
            $this->query($this->signatoriesRepository, SignatoriesResource::class, $request),
            true
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SignatoryStoreRequest $request)
    {
        //
        $data = $request->all();
        if ($request->hasFile('e_signature')) {

            $image = $request->file('e_signature');
            $data['e_signature'] = file_get_contents($image->getRealPath());
            $data['e_signature_mime_type'] = $image->getClientMimeType();
            $data['e_signature_filename'] = $image->getClientOriginalName();
        }
        $this->catch(fn(): mixed => $this->signatoriesRepository->create($data), false);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SignatoryStoreRequest $request, Signatories $signatory)
    {
        $request->user()->throwCannot("Setup.Signatories", "edit");
        return $this->catch(fn(): mixed => $this->signatoriesRepository->update($request->only([
            'name',
            'position_id',
            'department_id',
            'attachment',
        ]), $signatory->uuid));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Signatories $signatory, Request $request)
    {
        $request->user()->throwCannot("Setup.Signatories", "Delete");

        $this->catch(fn(): mixed => $this->signatoriesRepository->delete($signatory->uuid, 'uuid'), expectResponse: false);
    }
}
