<?php
namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Supports\Utils\Upload;
use DB;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderRepository extends Repository
{
    use Upload;
    use Conditions\OrderConditions;

    public function __construct(protected Order $model)
    {

    }

    public function create(array $data): ?Order
    {
        // throw new HttpResponseException(response()->json([
        //     'message' => $data
        // ], 500));
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

    public function update(array $data, $id, $key = 'uuid'): Order
    {
        return DB::transaction(function () use ($data, $id) {
            /**
             * Place the data['items'] to a variable and remove it from the data array
             * before updating the header 
             * */
            $items = $data['items'] ?? [];
            unset($data['items']);

            // Upon succes on removing the items from the data array, update the header
            $header = $this->updateHeader($data, $id);

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
     * Store header and file uploaded
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function storeHeader(array $data)
    {
        $model = parent::create($data);

        $file = $data['attachment'] ?? null;

        if ($file) {
            unset($data['attachment']);

            if ($file = $this->upload($file, 'orders')) {
                $model->file()->associate($file);
            }
        }

        $model->save();
        $model->refresh();

        return $model;
    }

    /**
     * Summary of updateHeader
     * @param array $data
     * @param mixed $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function updateHeader(array $data, $id)
    {
        $file = $data['attachment'] ?? null;
        unset($data['attachment']);

        $model = parent::update($data, $id);

        if ($file) {
            if ($file = $this->upload($file, 'orders')) {
                $model->file()->associate($file);
            }
        }

        $model->save();
        $model->refresh();

        return $model;
    }

    /**
     * Summary of storeDetails
     * @param \App\Models\Order $header
     * @param array $details
     * @return \Illuminate\Database\Eloquent\Collection<int, TRelatedModel>
     */
    protected function storeItems(Order $header, array $items)
    {
        return $header->details()->saveMany($this->items($items));
    }

    protected function deleteItems(Order $header)
    {
        $details = OrderDetail::where('order_id', $header->id)->get();
        foreach ($details as $detail) {
            $detail->delete();
        }
    }

    protected function items(array $items)
    {
        return array_map(function (array $row) {
            return new OrderDetail([
                'product_id' => $row['product_id'],
                'product_expense_id' => $row['product_expense_id'],
                'rate' => $row['rate'],
                'quantity' => $row['quantity'],
                'product_name' => $row['product_name'],
                'product_description' => $row['product_description'],
            ]);
        }, $items);
    }
}
