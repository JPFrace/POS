<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\ChartAccounts\StoreChartAccountRequest;
use App\Http\Requests\Accounting\ChartAccounts\UpdateChartAccountRequest;
use App\Http\Resources\Accounting\ChartAccountResource;
use App\Models\ChartAccount;
use App\Repositories\ChartAccountsRepository;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\Relation;

class ChartAccountsController extends Controller
{
    public function __construct(protected ChartAccountsRepository $repository)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Accounting.Chart of Accounts", "List");

        return $this->query($this->repository, ChartAccountResource::class, $request);
    }

    public function show(ChartAccount $chartAccount, Request $request)
    {

        return $this->queryChartJournal(
            baseQuery: $chartAccount->journals(),
            resource: ChartAccountResource::class,
            request: $request,
            isCollection: true
        );
    }


    public function queryChartJournal(
        Relation $baseQuery,
        string $resource,
        Request $request,
        $isCollection = true
    ) {
        $query = $request->get('query', []);
        $query = is_array($query) ? $query : (array) json_decode($query);
        $groupBy = $request->get('group_by', []);
        $orderBy = $request->get('order_by', []);

        $orderBy = is_string($orderBy) ? json_decode($orderBy, true) : $orderBy;
        $groupBy = is_string($groupBy) ? json_decode($groupBy, true) : $groupBy;
        $startDate = $query['start_date'] ?? null;
        $endDate = $query['end_date'] ?? null;

        if ($startDate && $endDate) {
            $baseQuery->whereBetween('posted_at', [$startDate, $endDate]);
        }

        foreach ($query as $field => $value) {
            if (
                $value !== null && $value !== '' && !in_array($field, ['start_date', 'end_date'])
            ) {
                $baseQuery->where($field, 'like', "%{$value}%");
            }
        }

        foreach ($orderBy ?? [] as $order) {
            $baseQuery->orderBy($order['field'], $order['direction'] ?? 'asc');
        }

        $data = $baseQuery->paginate(
            $request->get('size', 10)
        );

        return $this->catch(
            callable: fn() =>
            $isCollection
            ? $resource::collection($data)
            : new $resource($data),
            expectResponse: true
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChartAccountRequest $request)
    {
        $request->user()->throwCannot("Accounting.Chart of Accounts", "Create");
        $this->catch(fn() => $this->repository->create($request->only([
            'name',
            'description',
            'code',
            'is_inactive',
            'type_id',
            'class_id',
            'parent_id',
            'dept_id',
            'usage_type_id',
            'balance',
            'usage_type_id',
            'add_as_product'
        ])));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChartAccountRequest $request, ChartAccount $chart_account)
    {
        $request->user()->throwCannot("Accounting.Chart of Accounts", "Edit");
        $this->catch(fn() => $this->repository->update($request->only([
            'name',
            'description',
            'code',
            'is_inactive',
            'type_id',
            'class_id',
            'parent_id',
            'usage_type_id',
            'dept_id',
            'balance',
        ]), $chart_account->uuid));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChartAccount $chart_account, Request $request)
    {
        $request->user()->throwCannot("Accounting.Chart of Accounts", "Delete");

        $this->repository->delete($chart_account->uuid);
    }
}
