<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setup\Department\StoreDepartmentRequest;
use App\Http\Requests\Setup\Department\UpdateDepartmentRequest;
use App\Http\Resources\Setup\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Repositories\DepartmentRepository;
use Illuminate\Http\Exceptions\HttpResponseException;

class DepartmentsController extends Controller
{

    public function __construct(protected DepartmentRepository $repository)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Setup.Departments", "List");

        return $this->query($this->repository, DepartmentResource::class, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request)
    {
        $request->merge(['created_by' => auth()->id()]);
        $this->repository->create($request->all());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $request->user()->throwCannot("Setup.Departments", "Edit");
        $this->repository->update($request->all(), $department->uuid);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department, Request $request)
    {
        $request->user()->throwCannot("Setup.Departments", "Delete");

        try {
            $department->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23000') {
                return response()->json([
                    'message' => 'Cannot delete department: ' . $department->name . '. It is referenced by other records.'
                ], 409);
            }
            throw $e;
        }
    }
}