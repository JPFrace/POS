<?php

namespace App\Repositories;

use App\Models\Deposit;
use App\Models\DepositDetail;
use App\Models\OfficialReceipt;
use App\Supports\Utils\Upload;
use Illuminate\Support\Facades\DB;

class DepositsRepository extends Repository implements \App\Contracts\Supports\Repository
{
    use Upload;


    public function __construct(protected Deposit $model)
    {

    }

    public function create(array $data): ?Deposit
    {
        return DB::transaction(function () use ($data) {
            $items = $data['items'];

            unset($data['items']);

            $header = $this->storeHeader($data);

            $this->storeItems($header, $items);

            $header->amount = $header->total;
            $header->save();

            $this->depositInTransit($header);

            return $header;
        });
    }

    /**
     * Summary of update
     * @param array $data
     * @param mixed $id
     * @param mixed $key
     * @return \App\Models\Deposit
     */
    public function update(array $data, $id, $key = 'uuid'): Deposit
    {
        return DB::transaction(function () use ($data, $id) {
            /**
             * Place the data['items'] to a variable and remove it from the data array
             * before updating the header 
             * */
            $items = $data['items'];
            unset($data['items']);

            // Upon succes on removing the items from the data array, update the header
            $header = $this->updateHeader($data, $id);

            // Get the existing details and delete the immutable details as per standard procedure
            $this->deleteItems($header);

            // After deleting the immutable details, store the new items

            $this->storeItems($header, $items);

            $header->amount = $header->total;
            $header->save();

            $this->depositInTransit($header);

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
    protected function storeHeader(array $data)
    {

        $model = parent::create($data);

        $file = $data['attachment'] ?? null;

        if ($file) {
            unset($data['attachment']);

            if ($file = $this->upload($file, 'deposits')) {
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
            if ($file = $this->upload($file, 'payments')) {
                $model->file()->associate($file);
            }
        }

        $model->save();
        $model->refresh();

        return $model;
    }

    /**
     * Summary of storeDetails
     * @param \App\Models\Deposit $header
     * @param array $details
     * @return \Illuminate\Database\Eloquent\Collection<int, TRelatedModel>
     */
    protected function storeItems(Deposit $header, array $items)
    {
        return $header->details()->saveMany($this->items($items));
    }

    /**
     * Summary of deleteItems
     * @param \App\Models\Deposit $header
     * @return void
     */
    protected function deleteItems(Deposit $header)
    {
        foreach ($header->details() as $detail) {
            $detail->delete();
        }
    }

    protected function items(array $items)
    {
        return array_map(function (array $row) {
            $or = OfficialReceipt::where('id', $row['official_receipt_id'])->first();

            throw_if(empty($or), new \Exception("Invalid transaction."));

            throw_if(!empty($or->deposit_transit_at), new \Exception("Ref No. [{$or->ref_no}] deposit is in transit."));

            return new DepositDetail([
                'transactable_type' => OfficialReceipt::class,
                'transactable_id' => $or->id,
                'contact_idno' => $or->customer_idno,
                'payment_method_id' => $or->payment_method_id,
                'rate' => $or->actual_receive_amount,
                'memo' => $row['memo'],
                'ref_no' => $or->ref_no,
                'date' => $or->date
            ]);
        }, $items);
    }

    protected function depositInTransit(Deposit $header)
    {
        foreach ($header->details as $detail) {
            $transactable = $detail->transactable;
            $transactable->deposit_transit_at = now();
            $transactable->save();
        }
    }
}