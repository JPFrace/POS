<?php

namespace App\Http\Controllers\Setup;

use App\Models\Access;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Repositories\GlobalRepository;
use App\Http\Resources\Setup\AccessResource;
use App\Http\Requests\Setup\Access\AccessStoreRequest;
use App\Http\Requests\Setup\Access\AccessUpdateRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AccessController extends Controller
{
    private GlobalRepository $access;

    public function __construct()
    {
        $this->access = new GlobalRepository(new Access());
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Security.Access", "List");

        return $this->query($this->access, AccessResource::class, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AccessStoreRequest $request)
    {
        return $this->catch(fn() => $this->access->create($request->only([
            'name',
            'description',
            'active',
        ])), true);
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
    public function update(AccessUpdateRequest $request, Access $access)
    {

        return $this->catch(fn(): mixed => $this->access->update($request->only([
            'name',
            'description',
            'active',
        ]), $access->id), expectResponse: true);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Access $access, Request $request)
    {
        $request->user()->throwCannot("Security.Access", "Delete");

        return $this->catch(fn() => $this->access->delete($access->uuid), true);
    }

    public function destroySelected(Request $request)
    {
        $request->user()->throwCannot("Security.Access", "Delete");

        $request->validate([
            'selected' => 'required|array',
            'selected.*' => Rule::exists('access', 'uuid')
        ]);

        return $this->catch(fn() => $this->access->delete($request->get('selected')), true);
    }
}
