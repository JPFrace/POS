<?php
namespace App\Repositories;

use App\Models\Bill;
use App\Models\BillDetail;
use App\Supports\Utils\Upload;
use Illuminate\Support\Facades\DB;

class BillRepository extends Repository implements \App\Contracts\Supports\Repository
{
    use Upload;
    use Conditions\BillConditions;

    public function __construct(protected Bill $model)
    {
    }

    public function create(array $data): ?Bill
    {
        return DB::transaction(function () use ($data) {
            $items = $data['items'];

            unset($data['items']);

            $header = $this->storeHeader($data);

            $this->storeItems($header, $items);

            $header->amount = $header->total;
            $header->save();

            return $header;
        });
    }

    public function update(array $data, $id, $key = 'uuid'): Bill
    {
        return DB::transaction(function () use ($data, $id, $key) {
            /**
             * Place the data['items'] to a variable and remove it from the data array
             * before updating the header 
             * */
            $items = $data['items'];
            unset($data['items']);

            // Upon succes on removing the items from the data array, update the header
            $header = $this->updateHeader($data, $id, $key);

            // Get the existing details and delete the immutable details as per standard procedure
            $this->deleteItems($header);

            // After deleting the immutable details, store the new items
            $this->storeItems($header, $items);

            $header->amount = $header->total;
            $header->save();

            return $header;
        });
    }

    /**
     * Delete transaction
     * @param string|int|array $id
     * @param mixed $key
     * @return bool|null
     */
    public function delete(string|int|array $id, $key = 'uuid'): bool|null
    {
        $id = is_array($id) ? $id : [$id];
        $headers = $this->model()->whereIn($key, $id)->get();

        foreach ($headers as $header) {
            $this->deleteItems($header);
        }

        return parent::delete($id, $key);
    }

    /**
     * Store header and file uploaded
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function storeHeader(array $data): Bill
    {
        $model = parent::create($data);

        $file = $data['attachment'] ?? null;

        if ($file) {
            unset($data['attachment']);

            if ($file = $this->upload($file, 'bills')) {
                $model->file()->associate($file);
            }
        }

        $model->save();
        $model->refresh();

        return $model;
    }

    protected function updateHeader(array $data, $id, $key): Bill
    {
        $file = $data['attachment'] ?? null;
        unset($data['attachment']);

        $model = parent::update($data, $id, $key);

        if ($file) {
            unset($data['attachment']);

            if ($file = $this->upload($file, 'bills')) {
                $model->file()->associate($file);
            }
        } else {
            $model->file()->dissociate();
        }

        $model->save();
        $model->refresh();

        return $model;
    }

    /**
     * Summary of storeItems
     * @param \App\Models\Bill $header
     * @param array $items
     * @return BillDetail[]|\Traversable<int|string, BillDetail>
     */
    protected function storeItems(Bill $header, array $items)
    {
        return $header->details()->saveMany($this->items($items));
    }

    /**
     * Summary of deleteItems
     * @param \App\Models\Bill $header
     * @return void
     */
    protected function deleteItems(Bill $header)
    {
        $details = BillDetail::where('bill_id', $header->id)->get();
        foreach ($details as $detail) {
            $detail->delete();
        }
    }

    protected function items(array $items)
    {
        return array_map(function (array $row) {
            return new BillDetail([
                'order_id' => $row['order_id'],
                'product_id' => $row['product_id'],
                'product_expense_id' => $row['product_expense_id'],
                'rate' => $row['rate'],
                'quantity' => $row['quantity'],
                'product_name' => $row['product_name'],
                'product_description' => $row['product_description'],
            ]);
        }, $items);
    }

    public function findByUuids(array $uuids)
    {
        return Bill::whereIn('uuid', $uuids)->get();
    }
}
