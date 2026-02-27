<?php

namespace App\Http\Controllers\budgeting;

use App\Http\Controllers\Controller;
use App\Http\Requests\budget\StoreBudgetPeriodRequest;
use App\Http\Resources\Budgeting\BudgetResource;
use App\Repositories\BudgetPeriodRepository;
use Illuminate\Http\Request;

class BudgetPeriodsController extends Controller
{
    public function __construct(protected BudgetPeriodRepository $repository) {}
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
    public function store(StoreBudgetPeriodRequest $request)
    {
        //
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
    public function update(StoreBudgetPeriodRequest $request, string $budget_period)
    {
        return  $this->catch(fn(): mixed => $this->repository->update($request->only([
            'period_1',
            'period_2',
            'period_3',
            'period_4',
            'period_5',
            'period_6',
            'period_7',
            'period_8',
            'period_9',
            'period_10',
            'period_11',
            'period_12'
        ]), $budget_period, ), true);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
