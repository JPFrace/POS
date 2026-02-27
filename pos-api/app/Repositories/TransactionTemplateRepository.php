<?php
namespace App\Repositories;

use App\Models\TransactionTemplate;
use App\Models\TransactionTemplateDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionTemplateRepository extends Repository
{
    public function __construct(protected TransactionTemplate $model)
    {

    }

    public function create(array $data): TransactionTemplate
    {
        return DB::transaction(function () use ($data) {
            $details = $data['details'];
            unset($data['details']);

            $data['creator_id'] = Auth::id();
            $template = $this->model->create($data);

            $this->storeDetails($template, $details);

            return $template->load('details.product');
        });
    }

    public function update(array $data, $id, $key = 'uuid'): TransactionTemplate
    {
        return DB::transaction(function () use ($data, $id, $key) {
            $details = $data['details'] ?? [];
            unset($data['details']);

            $template = $this->findByUuid($id, $key);
            $template->update($data);

            $this->deleteDetails($template);
            $this->storeDetails($template, $details);

            return $template->load('details.product');
        });
    }

    public function delete(string|int|array $id, $key = 'uuid'): bool
    {
        return DB::transaction(function () use ($id, $key) {
            $template = $this->findByUuid($id, $key);
            return (bool) $template->delete();
        });
    }

    protected function storeDetails(TransactionTemplate $template, array $details): void
    {
        $template->details()->createMany(
            collect($details)->map(fn(array $row) => [
                'product_id' => $row['product_id'],
                'quantity' => $row['quantity'] ?? 1,
                'amount' => $row['amount'] ?? null,
                'creator_id' => Auth::id(),
            ])->all()
        );
    }

    protected function deleteDetails(TransactionTemplate $template): void
    {
        $template->details()->delete();
    }
}