<?php

namespace App\Repositories;

use App\Enums\TransType as EnumsTransType;
use App\Models\Dimension;
use App\Models\OfficialReceipt;
use App\Models\OfficialReceiptDenomination;
use App\Models\OfficialReceiptDetail;
use App\Models\Taxonomy;
use App\Models\TransType;
use App\Supports\Utils\Upload;
use Illuminate\Support\Facades\DB;

class OfficialReceiptRepository extends Repository implements \App\Contracts\Supports\Repository
{
    use Upload;
    use Conditions\OfficialReceiptsConditions;

    public function __construct(protected OfficialReceipt $model)
    {
    }

    public function create(array $data): ?OfficialReceipt
    {
        $items = $data['items'] ?? [];
        $dimensions = $data['dimensions'] ?? [];
        $denominations = $data['denominations'] ?? [];

        unset($data['items'], $data['dimensions'], $data['denominations']);

        $header = $this->storeHeader($data);

        $this->storeItems($header, $items);

        if ($denominations) {
            $this->storeDenominations($header, $denominations);
        }

        if ($dimensions) {
            $this->storeDimensions($header, $dimensions);
        }

        $header->amount = $header->total;
        $header->gross_amount = $header->total;
        $header->actual_receive_amount = $header->total_cash_in_bank;
        $header->status()->associate(Taxonomy::transactionUnposted()->first());
        $header->save();

        return $header;

    }

    /**
     * Summary of update
     * @param array $data
     * @param mixed $id
     * @param mixed $key
     * @return \App\Models\OfficialReceipt
     */
    public function update(array $data, $id, $key = 'uuid'): OfficialReceipt
    {
        /**
         * Place the data['items'] to a variable and remove it from the data array
         * before updating the header 
         * */
        $items = $data['items'] ?? [];
        $dimensions = $data['dimensions'] ?? [];
        $denominations = $data['denominations'] ?? [];

        unset($data['items'], $data['dimensions'], $data['denominations']);

        // Upon succes on removing the items from the data array, update the header
        $header = $this->updateHeader($data, $id);

        // Get the existing details and delete the immutable details as per standard procedure
        $this->deleteItems($header);

        // After deleting the immutable details, store the new items
        $this->storeItems($header, $items);

        if ($denominations) {
            $this->updateDenominations($header, $denominations);
        }

        if ($dimensions) {
            $this->updateDimensions($header, $dimensions);
        }

        $header->amount = $header->total;
        $header->gross_amount = $header->total;
        $header->actual_receive_amount = $header->total_cash_in_bank;
        $header->status()->associate(Taxonomy::transactionUnposted()->first());
        $header->save();

        return $header;
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
            $this->deleteDenominations($header);
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

            if ($file = $this->upload($file, 'official-receipts')) {
                $model->file()->associate($file);
            } else {
                $model->file()->dissociate();
            }
        }

        $model->save();
        $model->refresh();

        return $model;
    }

    protected function updateHeader(array $data, $id)
    {
        $file = $data['attachment'] ?? null;
        unset($data['attachment']);

        $model = parent::update($data, $id);

        if ($file) {
            if ($file = $this->upload($file, 'official-receipts')) {
                $model->file()->associate($file);
            }
        }

        $model->save();
        $model->refresh();

        return $model;
    }

    /**
     * Summary of storeDetails
     * @param \App\Models\OfficialReceipt $header
     * @param array $details
     * @return \Illuminate\Database\Eloquent\Collection<int, TRelatedModel>
     */
    protected function storeItems(OfficialReceipt $header, array $items)
    {
        return $header->details()->saveMany($this->items($items));
    }

    /**
     * Summary of deleteItems
     * @param \Illuminate\Database\Eloquent\Collection $orDetails
     * @return void
     */
    public function deleteItems(OfficialReceipt $model)
    {
        foreach ($model->details as $detail) {
            $detail->delete();
        }
    }

    protected function items(array $items)
    {
        return array_map(function (array $row) {
            return new OfficialReceiptDetail([
                'trans_type' => $row['trans_type'],
                'ref_no' => $row['ref_no'],
                'product_id' => $row['product_id'],
                'product_income_id' => $row['product_income_id'],
                'rate' => $row['rate'],
                'withholding_tax_rate' => $row['withholding_tax_rate'] ?? 0,
                'sales_tax_rate' => $row['sales_tax_rate'] ?? 0,
                'quantity' => $row['quantity'],
                'product_name' => $row['product_name'],
                'product_description' => $row['product_description'],
                'withholding_tax_account_id' => $row['withholding_tax_account_id'] ?? null,
                'sales_tax_account_id' => $row['sales_tax_account_id'] ?? null,
            ]);
        }, $items);
    }

    /**
     * Store denominations for an official receipt.
     * @param \App\Models\OfficialReceipt $header
     * @param array $denominations
     * @return \Illuminate\Database\Eloquent\Collection<int, \App\Models\OfficialReceiptDenomination>
     */
    protected function storeDenominations(OfficialReceipt $header, array $denominations)
    {
        return $header->denominations()->saveMany($this->denominationRows($denominations));
    }

    /**
     * Delete all denominations for an official receipt.
     * @param \App\Models\OfficialReceipt $header
     * @return void
     */
    public function deleteDenominations(OfficialReceipt $header): void
    {
        $header->denominations()->delete();
    }

    /**
     * Update denominations: delete existing and store new.
     * @param \App\Models\OfficialReceipt $header
     * @param array $denominations
     * @return void
     */
    protected function updateDenominations(OfficialReceipt $header, array $denominations): void
    {
        $this->deleteDenominations($header);
        $this->storeDenominations($header, $denominations);
    }

    /**
     * Build OfficialReceiptDenomination models from request rows.
     * @param array $denominations
     * @return array<int, \App\Models\OfficialReceiptDenomination>
     */
    protected function denominationRows(array $denominations): array
    {
        $fillable = array_diff(
            (new OfficialReceiptDenomination)->getFillable(),
            ['or_id']
        );

        return array_map(function (array $row) use ($fillable): OfficialReceiptDenomination {
            $attributes = array_intersect_key($row, array_fill_keys($fillable, true));

            return new OfficialReceiptDenomination($attributes);
        }, $denominations);
    }

    protected function storeDimensions(OfficialReceipt $header, array $dimensions)
    {
        foreach ($dimensions as $dimension) {
            $transTypeID = TransType::where('code', EnumsTransType::COLLECTION)->first()->id;
            $dimensionID = Dimension::where('uuid', $dimension['id'])->first()->id;
            $header->transactionDimensions()->create([
                'trans_type' => $transTypeID,
                'dimension_id' => $dimensionID
            ]);
        }
    }

    protected function updateDimensions(OfficialReceipt $header, array $data)
    {
        $header->transactionDimensions()->delete();

        $this->storeDimensions($header, $data);
    }

    public function findByUuids(array $uuids)
    {
        return OfficialReceipt::whereIn('uuid', $uuids)->get();
    }
}
