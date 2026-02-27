<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Http\Resources\Security\AuditResource;
use App\Models\User;
use App\Repositories\AuditRepository;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function __construct(protected AuditRepository $repository)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Security.Audit Trails", "List");

        return $this->catch(fn(): mixed => $this->query(
            $this->repository,
            AuditResource::class,
            $request
        ), expectResponse: true);
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
    public function show(User $audit_trail)
    {
        return $this->catch(fn() => $this->repository->getActivities($audit_trail), expectResponse: true);
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
    public function destroy(string $id)
    {
        //
    }
}
