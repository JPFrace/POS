<?php

namespace App\Repositories;
use App\Models\External;
use App\Models\ExternalTransaction;
use Illuminate\Support\Facades\DB;

class ExternalRepository extends Repository
{
    public function __construct(protected External $model)
    {

    }

    public function create(array $data): ?External
    {
        return DB::transaction(function () use ($data) {
            $transactions = $data['transactions'];

            unset($data['transactions']);

            $header = $this->storeHeader($data);

            $this->storeTransactions($header, $transactions);

            $header->save();
        });
    }

    /**
     * Summary of update
     * @param array $data
     * @param mixed $id
     * @param mixed $key
     * @return \App\Models\External
     */
    // public function update(array $data, $id, $key = 'uuid'): External
    // {
    //     return DB::transaction(function () use ($data, $id) {
    //         /**
    //          * Place the data['items'] to a variable and remove it from the data array
    //          * before updating the header 
    //          * */
    //         $items = $data['items'] ?? [];
    //         unset($data['items']);

    //         // Upon succes on removing the items from the data array, update the header
    //         $header = $this->updateHeader($data, $id);

    //         // Get the existing details and delete the immutable details as per standard procedure
    //         $this->deleteItems($header);

    //         // After deleting the immutable details, store the new items
    //         $this->storeItems($header, $items);

    //         $header->amount = $header->total;
    //         $header->save();

    //         return $header;
    //     });
    // }

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
        $file = $data['attachment'] ?? null;
        unset($data['attachment']);

        $model = parent::update($data, $id);

        $model->save();
        $model->refresh();

        return $model;
    }

    /**
     * Summary of storeItems
     * @param \Illuminate\Database\Eloquent\Model $header
     * @param array $items
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function storeTransactions(External $header, array $transactions)
    {
        return $header->transactions()->saveMany($this->transaction($transactions));
    }

    /**
     * Summary of deleteItems
     * @param \Illuminate\Database\Eloquent\Collection $orDetails
     * @return void
     */
    protected function deleteItems(External $header)
    {
        $details = ExternalTransaction::where('or_id', $header->id)->get();
        foreach ($details as $detail) {
            $detail->delete();
        }
    }

    protected function transaction(array $items)
    {
        return array_map(function (array $row) {
            return new ExternalTransaction([
                'code' => $row['code'],
                'particular' => $row['particular'],
                'debit' => $row['debit'],
                'credit' => $row['credit'],
                'document_date' => $row['document_date'],
                'cost_center' => $row['cost_center'],
                'contact_name' => $row['contact_name'],
                'contact_id_no' => $row['contact_id_no'],
                'ref_no' => $row['ref_no'],
            ]);
        }, $items);
    }
}