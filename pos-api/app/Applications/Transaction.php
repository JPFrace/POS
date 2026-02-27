<?php

namespace App\Applications;

use App\Contracts\Supports\Repository;
use App\Core\Transaction as CoreTransaction;
use App\Enums\PostingStatus;
use App\Models\User;
use App\Services\Logger;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Transaction
{
    private CoreTransaction $service;

    public function __construct(protected User $user, protected Repository $repository)
    {
        $this->service = new CoreTransaction($user, $repository);
    }

    /**
     * Create
     * @return TFirstDefault|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $data): ?Model
    {
        $transaction = DB::transaction(function () use ($data) {
            return $this->service->create($data);
        });

        Logger::log()
            ->page(get_class($transaction))
            ->action(__FUNCTION__)
            ->path(__METHOD__)
            ->reference(reference: [
                'id' => $transaction->id,
                'ref_no' => $transaction->getRefNo()
            ])
            ->create();

        return $transaction;
    }

    /**
     * Update
     * @param string $uuid
     * @param array $data
     * @return TFirstDefault|\Illuminate\Database\Eloquent\Model
     */
    public function update(string $uuid, array $data): ?Model
    {
        $transaction = DB::transaction(function () use ($data, $uuid) {
            return $this->service->update($uuid, $data);
        });

        Logger::log()
            ->page(get_class($transaction))
            ->action(__FUNCTION__)
            ->path(__METHOD__)
            ->reference(reference: [
                'id' => $transaction->id,
                'ref_no' => $transaction->getRefNo()
            ])
            ->create();

        return $transaction;
    }


    /**
     * Delete transaction
     * @param string $uuid
     * @param array $
     */
    public function delete(string $uuid)
    {
        $transaction = DB::transaction(function () use ($uuid) {
            return $this->service->delete($uuid);
        });

        Logger::log()
            ->page(get_class($transaction))
            ->action(__FUNCTION__)
            ->path(__METHOD__)
            ->reference(reference: [
                'id' => $transaction->id,
                'ref_no' => $transaction->getRefNo(),
            ])
            ->create();

        return $transaction;
    }

    /**
     * Update posting status
     * @param PostingStatus $status
     * @param array $transactions
     * @param string $key
     * @throws \Exception
     * @return int
     */
    public function updateStatus(PostingStatus $status, array $transactions): int
    {
        $method = __FUNCTION__;
        $path = __METHOD__;

        $transactions = DB::transaction(function () use ($transactions, $status, $method, $path) {
            foreach ($transactions as $uuid) {
                $transaction = $this->service->updateStatus($status, $uuid);

                Logger::log()
                    ->page(get_class($transaction))
                    ->action($method)
                    ->path($path)
                    ->reference([
                        'id' => $transaction->id,
                        'ref_no' => $transaction->getRefNo(),
                        'new_status' => $transaction->status->name,
                    ])
                    ->create();
            }

            return $transactions;
        });

        return count($transactions);
    }
}