<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Http\Requests\Security\Positions\PositionStoreRequest;
use App\Http\Requests\Security\Positions\PositionUpdateRequest;
use App\Http\Resources\Security\PositionResource;
use App\Models\Position;
use App\Repositories\PositionsRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class PositionsController extends Controller
{
    public function __construct(protected PositionsRepository $repository)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Security.User's Position", "List");

        return $this->query($this->repository, PositionResource::class, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PositionStoreRequest $request)
    {
        return $this->catch(fn() => $this->repository->create($request->only([
            'code',
            'title',
            'is_inactive',
        ])));
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
    public function update(PositionUpdateRequest $request, Position $userPosition)
    {
        return $this->catch(fn() => $this->repository->update($request->only([
            'code',
            'title',
            'is_inactive',
        ]), $userPosition->uuid));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $userPosition, Request $request)
    {
        $request->user()->throwCannot("Security.User's Position", "Delete");

        $this->catch(fn(): mixed => $this->repository->delete($userPosition->uuid));
    }
}
