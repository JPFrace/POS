<?php
namespace App\Repositories;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Taxonomy;
use App\Supports\Utils\Upload;
use Illuminate\Support\Facades\DB;

class InvoiceRepository extends Repository implements \App\Contracts\Supports\Repository
{
    use Upload;
    use Conditions\InvoiceConditions;

    public function __construct(protected Invoice $model)
    {

    }

    public function create(array $data): ?Invoice
    {
        $items = $data['items'];
        unset($data['items']);

        $data['status_id'] = Taxonomy::transactionUnposted()->first()->id;

        $header = $this->storeHeader($data);
        $this->storeItems($header, $items);

        $header->amount = $header->total;
        $header->save();

        return $header;
    }

    /**
     * Summary of update
     * @param array $data
     * @param mixed $id
     * @param mixed $key
     * @return \App\Models\Invoice
     */
    public function update(array $data, $id, $key = 'uuid'): Invoice
    {
        /**
         * Place the data['items'] to a variable and remove it from the data array
         * before updating the header 
         * */
        $items = $data['items'] ?? [];
        unset($data['items']);

        $data['status_id'] = Taxonomy::transactionUnPosted()->first()->id;

        // After remove the items from the data array, update the header first
        $header = $this->updateHeader($data, $id);

        // Get the existing details and delete the immutable details as per standard procedure
        $this->deleteItems($header);
        // After deleting the immutable details, add the new details
        $this->storeItems($header, $items);
        // Finally, update the header amount
        $header->amount = $header->total;
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

            if ($file = $this->upload($file, 'invoices')) {
                $model->file()->associate($file);
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
            if ($file = $this->upload($file, 'invoices')) {
                $model->file()->associate($file);
            }
        }

        $model->save();
        $model->refresh();

        return $model;
    }

    protected function deleteItems(Invoice $header)
    {
        $details = InvoiceDetail::where('invoice_id', $header->id)->get();
        foreach ($details as $detail) {
            $detail->delete();
        }
    }

    /**
     * Summary of storeDetails
     * @param \App\Models\Invoice $header
     * @param array $details
     * @return \Illuminate\Database\Eloquent\Collection<int, TRelatedModel>
     */
    protected function storeItems(Invoice $header, array $items)
    {
        return $header->details()->saveMany($this->items($items));
    }

    protected function items(array $items)
    {
        return array_map(function (array $row) {
            return new InvoiceDetail([
                'product_id' => $row['product_id'],
                'product_income_id' => $row['product_income_id'],
                'product_receivable_id' => $row['product_receivable_id'],
                'rate' => $row['rate'],
                'tax_rate' => $row['tax_rate'],
                'quantity' => $row['quantity'],
                'product_name' => $row['product_name'],
                'product_description' => $row['product_description'],
            ]);
        }, $items);
    }

    public function findByUuids(array $uuids)
    {
        return Invoice::whereIn('uuid', $uuids)->get();
    }
}
