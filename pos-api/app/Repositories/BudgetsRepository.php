<?php

namespace App\Repositories;

use App\Enums\BudgetStatus;
use App\Models\Budget;
use App\Models\BudgetDetail;
use App\Models\ChartAccount;
use App\Supports\Utils\Amount;
use Illuminate\Support\Facades\DB;

class BudgetsRepository extends Repository
{

    use Conditions\BudgetConditions;
    public function __construct(protected Budget $model) {}

    public function create(array $data): ?Budget
    {
        return DB::transaction(function () use ($data) {
            $items = $data['items'] ?? [];

            $header = $this->storeHeader($data);

            $this->storeItems($header, $items);

            $header->save();

            return $header;
        });
    }

    /**
     * Summary of update
     * @param array $data
     * @param mixed $id
     * @param mixed $key
     * @return \App\Models\Budget
     */
    public function update(array $data, $id, $key = 'uuid'): Budget
    {
        return DB::transaction(function () use ($data, $id) {
            /**
             * Place the data['items'] to a variable and remove it from the data array
             * before updating the header 
             * */
            $items = $data['items'] ?? [];
            unset($data['items']);

            $header = $this->updateHeader($data, $id);

            // Get the existing details and delete the immutable details as per standard procedure
            $this->deleteItems($header);

            // After deleting the immutable details, store the new items
            $this->storeItems($header, $items);

            $header->save();

            return $header;
        });
    }

    /**
     * Store header and file uploaded
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function storeHeader(array $data)
    {
        $model = parent::create($data);

        $model->save();
        $model->refresh();

        return $model;
    }

    protected function updateHeader(array $data, $id)
    {
        $model = parent::update($data, $id);

        $model->save();
        $model->refresh();

        return $model;
    }

    /**
     * Summary of storeDetails
     * @param \App\Models\Budget $header
     * @param array $details
     * @return \Illuminate\Database\Eloquent\Collection<int, TRelatedModel>
     */
    protected function storeItems(Budget $header, array $items)
    {
        return $header->details()->saveMany($this->items($items));
    }

    protected function updateItems(Budget $header, array $items)
    {
        foreach ($items as $item) {
            $header->details()->updateOrCreate(
                ['id' => $item['id'] ?? null],
                $item
            );
        }
    }

    /**
     * Summary of deleteItems
     * @param \Illuminate\Database\Eloquent\Collection<int, \App\Models\BudgetDetail> $header
     * @return void
     */
    protected function deleteItems(Budget $header)
    {
        $details = BudgetDetail::where('budget_id', $header->id)->get();
        foreach ($details as $detail) {
            $detail->periods()->delete();
            $detail->delete();
        }
    }

    protected function items(array $items)
    {
        return array_map(function (array $row) {
            return new BudgetDetail([
                'chart_account_id' => (int)$row['chart_account_id'],
                'name' => $row['name'],
                'amount' => number_format(Amount::acceptable($row['amount']), 2, '.', ''),
                'description' => $row['description'] ?? "",
            ]);
        }, $items);
    }

    public function post(Budget $header): Budget
    {
        if ($header->status_id === BudgetStatus::POSTED) {
            return $header;
        }

        $header->status_id = BudgetStatus::POSTED;
        $header->save();

        $header->load('details');
        foreach ($header->details as $detail) {
            ChartAccount::updateOrCreate(
                [
                    'id' => $detail->chart_account_id,
                ],
                [
                    'budget' => $detail->amount,
                    'balance' => $detail->amount,
                ]
            );
        }

        return $header;
    }

    public function unpost(Budget $header): Budget
    {
        if ($header->status_id === BudgetStatus::UNPOSTED) {
            return $header;
        }

        $header->status_id = BudgetStatus::UNPOSTED;
        $header->save();        

        return $header;
    }

    public function saveAsNew(Budget $header): Budget
    {
        $newHeader = $header->replicate();
        $newHeader->name = $header->name . ' (Copy)';
        $newHeader->status_id = BudgetStatus::UNPOSTED;
        $newHeader->push();

        foreach ($header->details as $detail) {
            $newDetail = $detail->replicate();
            $newDetail->budget_id = $newHeader->id;
            $newDetail->push();

            $period = $detail->periods()->get();
            foreach ($period as $periodItem) {
                $newPeriod = $periodItem->replicate();
                $newPeriod->budget_detail_id = $newDetail->id;
                $newPeriod->push();
            }
        }    

        return $newHeader;
    }
}
