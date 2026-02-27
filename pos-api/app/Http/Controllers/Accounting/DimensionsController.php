<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\Dimension\DimensionStoreRequest;
use App\Http\Requests\Accounting\Dimension\DimensionUpdateRequest;
use App\Http\Resources\Accounting\DimensionResource;
use App\Models\Dimension;
use App\Repositories\DimensionRepository;
use Illuminate\Http\Request;

class DimensionsController extends Controller
{
    public function __construct(protected DimensionRepository $repository)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Accounting.Dimensions", "List");

        return $this->query($this->repository, DimensionResource::class, $request);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DimensionStoreRequest $request)
    {
        $this->catch(fn() => $this->repository->create($request->only([
            'name',
            'description',
            'is_inactive'
        ])));
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
    public function update(DimensionUpdateRequest $request, Dimension $dimension)
    {
        $this->catch(fn() => $this->repository->update($request->only([
            'name',
            'description',
            'is_inactive'
        ]), $dimension->uuid));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dimension $dimension, Request $request)
    {
        $request->user()->throwCannot("Accounting.Dimensions", "Delete");

        $this->catch(fn() => $this->repository->delete($dimension->uuid, 'uuid'));
    }
}
