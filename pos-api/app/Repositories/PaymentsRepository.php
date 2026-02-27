<?php

namespace App\Repositories;

use App\Enums\TransType as EnumsTransType;
use App\Models\Dimension;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\TransType;
use App\Supports\Utils\Upload;
use Illuminate\Support\Facades\DB;

class PaymentsRepository extends Repository implements \App\Contracts\Supports\Repository
{
    use Upload;
    use Conditions\PaymentsConditions;



    public function __construct(protected Payment $model)
    {

    }

    public function create(array $data): ?Payment
    {
        return DB::transaction(function () use ($data) {
            $items = $data['items'];
            $dimensions = $data['dimensions'] ?? [];
            unset($data['items'], $data['dimensions']);

            $header = $this->storeHeader($data);

            $this->storeItems($header, $items);

            if ($dimensions) {
                $this->storeDimensions($header, $dimensions);
            }


            $header->amount = $header->total;
            $header->save();

            return $header;
        });
    }

    /**
     * Summary of update
     * @param array $data
     * @param mixed $id
     * @param mixed $key
     * @return \App\Models\Payment
     */
    public function update(array $data, $id, $key = 'uuid'): Payment
    {
        return DB::transaction(function () use ($data, $id) {
            /**
             * Place the data['items'] to a variable and remove it from the data array
             * before updating the header 
             * */
            $items = $data['items'];
            $dimensions = $data['dimensions'] ?? [];
            unset($data['items'], $data['dimensions']);

            // Upon succes on removing the items from the data array, update the header
            $header = $this->updateHeader($data, $id);

            // Get the existing details and delete the immutable details as per standard procedure
            $this->deleteItems($header);

            // After deleting the immutable details, store the new items
            $this->storeItems($header, $items);

            if ($dimensions) {
                $this->updateDimensions($header, $dimensions);
            }

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
    protected function storeHeader(array $data)
    {

        $model = parent::create($data);

        $file = $data['attachment'] ?? null;

        if ($file) {
            unset($data['attachment']);

            if ($file = $this->upload($file, 'payments')) {
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
     * @param \App\Models\Payment $header
     * @param array $details
     * @return \Illuminate\Database\Eloquent\Collection<int, TRelatedModel>
     */
    protected function storeItems(Payment $header, array $items)
    {
        return $header->details()->saveMany($this->items($items));
    }

    /**
     * Summary of deleteItems
     * @param \App\Models\Payment $header
     * @return void
     */
    protected function deleteItems(Payment $header)
    {
        $details = PaymentDetail::where('payment_id', $header->id)->get();
        foreach ($details as $detail) {
            $detail->delete();
        }
    }

    protected function items(array $items)
    {
        return array_map(function (array $row) {
            return new PaymentDetail([
                'product_id' => $row['product_id'],
                'product_expense_id' => $row['product_expense_id'],
                'rate' => $row['rate'],
                'withholding_tax_rate' => $row['withholding_tax_rate'] ?? 0.0,
                'quantity' => $row['quantity'],
                'product_name' => $row['product_name'],
                'product_description' => $row['product_description'],
                'contact_idno' => $row['contact_idno'] ?? null,
                'withholding_tax_account_id' => $row['withholding_tax_account_id'] ?? null
            ]);
        }, $items);
    }

    protected function storeDimensions(Payment $header, array $dimensions)
    {
        foreach ($dimensions as $dimension) {
            $transTypeID = TransType::where('code', EnumsTransType::COLLECTION->value)->first()->id;
            $dimensionID = Dimension::where('uuid', $dimension['id'])->first()->id;
            $header->transactionDimensions()->create([
                'trans_type' => $transTypeID,
                'dimension_id' => $dimensionID
            ]);
        }
    }

    protected function updateDimensions(Payment $header, array $data)
    {
        $header->transactionDimensions()->delete();

        $this->storeDimensions($header, $data);
    }

    public function findByUuids(array $uuids)
    {
        return Payment::whereIn('uuid', $uuids)->get();
    }
}