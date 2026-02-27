<?php
namespace App\Repositories;

use App\Models\JournalDetail;
use App\Models\JournalEntry;
use App\Supports\Utils\Upload;
use Illuminate\Support\Facades\DB;

class JournalEntriesRepository extends Repository implements \App\Contracts\Supports\Repository
{
    use Upload;
    use Conditions\JournalConditions;
    public function __construct(protected JournalEntry $model)
    {

    }

    public function create(array $data): ?JournalEntry
    {
        return DB::transaction(function () use ($data) {
            $items = $data['items'];

            unset($data['items']);

            $header = $this->storeHeader($data);

            $this->storeItems($header, $items);

            return $header;
        });
    }

    /**
     * Summary of update
     * @param array $data
     * @param mixed $id
     * @param mixed $key
     * @return \App\Models\JournalEntry
     */
    public function update(array $data, $id, $key = 'uuid'): JournalEntry
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

            if ($file = $this->upload($file, 'journals')) {
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
            if ($file = $this->upload($file, 'journals')) {
                $model->file()->associate($file);
            }
        }

        $model->save();
        $model->refresh();

        return $model;
    }

    protected function deleteItems(JournalEntry $header)
    {
        $details = JournalDetail::where('entry_id', $header->id)->get();
        foreach ($details as $detail) {
            $detail->delete();
        }
    }

    /**
     * Summary of storeDetails
     * @param \App\Models\JournalEntry $header
     * @param array $details
     * @return \Illuminate\Database\Eloquent\Collection<int, TRelatedModel>
     */
    protected function storeItems(JournalEntry $header, array $items)
    {
        return $header->details()->saveMany($this->items($items));
    }

    protected function items(array $items)
    {
        return array_map(function (array $row) {
            return new JournalDetail([
                'chart_account_id' => $row['chart_account_id'],
                'debit' => $row['debit'],
                'credit' => $row['credit'],
                'contact_name' => $row['contact_name'],
                'contact_type' => $row['contact_type'],
                'description' => $row['description'],
                'contact_idno' => $row['contact_idno'],
                'dept_id' => $row['dept_id'],
            ]);
        }, $items);
    }

    public function findByUuids(array $uuids)
    {
        return JournalEntry::whereIn('uuid', $uuids)->get();
    }
}