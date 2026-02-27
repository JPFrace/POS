<?php

namespace App\Http\Controllers\Budgeting;

use App\Enums\BudgetStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Budget\StoreBudgetRequest;
use App\Http\Requests\budget\UpdateBudgetRequest;
use App\Http\Resources\Budgeting\BudgetResource;
use App\Models\Budget;
use App\Repositories\BudgetsRepository;
use Illuminate\Http\Request;

class BudgetsController extends Controller
{

    public function __construct(protected BudgetsRepository $repository) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->query($this->repository, BudgetResource::class, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBudgetRequest $request)
    {
        return $this->catch(fn(): mixed => $this->repository->create($request->only([
            'name',
            'description',
            'department_id',
            'calendar_id',
            'type_id',
            'creator_id',
            'items'
        ])), true);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function post(Request $request, Budget $budget)
    {
        $request->user()->throwCannot("Budgeting.Budgets", "Post");

        abort_if(
            $budget->status_id === BudgetStatus::POSTED,
            422,
            'This record is already posted.'
        );

        return $this->catch(fn(): mixed => $this->repository->post($budget), true);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBudgetRequest $request, Budget $budget)
    {
        abort_if(
            $budget->status_id === BudgetStatus::POSTED,
            422,
            'Updating is not allowed. This budget is currently posted.'
        );

        return  $this->catch(fn(): mixed => $this->repository->update($request->only([
            'name',
            'description',
            'department_id',
            'calendar_id',
            'type_id',
            'creator_id',
            'items'
        ]), $budget->uuid), true);
    }

    public function unpost(Request $request, Budget $budget)
    {
        $request->user()->throwCannot("Budgeting.Budgets", "Unpost");

        abort_if(
            $budget->status_id !== BudgetStatus::POSTED,
            422,
            'Unposting is only allowed for posted records.'
        );

        return $this->catch(fn(): mixed => $this->repository->unpost($budget), true);
    }

    public function saveAsNew(Request $request, Budget $budget)
    {
        return $this->catch(fn(): mixed => $this->repository->saveAsNew($budget), true);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Budget $budget)
    {
        $request->user()->throwCannot("Budgeting.Budgets", "Delete");

        abort_if(
            $budget->status_id === BudgetStatus::POSTED,
            422,
            'Deletion not allowed. This record is currently posted.'
        );

        return $this->catch(fn(): mixed => $this->repository->delete($budget->id, 'id'), true);
    }
}
