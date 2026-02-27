<?php

namespace App\Core;

use App\Concerns\Transaction as ConcernsTransaction;
use App\Concerns\Transactions\StatusUpdatable;
use App\Contracts\Supports\Repository;
use App\Enums\PostingStatus;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Transaction
{
    public function __construct(protected User $user, protected Repository $repository)
    {

    }

    /**
     * Create transaction
     * @param array $data
     * @return Model|null
     */
    public function create(array $data): Model|null
    {
        return ConcernsTransaction::create($this->user, $this->repository)
            ->create($data);
    }
    /**
     * Update transaction
     * @param string $uuid
     * @param array $data
     * @return Model|null
     */
    public function update(string $uuid, array $data): Model|null
    {
        return ConcernsTransaction::update($this->user, $this->repository)
            ->update($uuid, $data);
    }

    /**
     * Delete transaction
     * @param string $uuid
     * @return Model|null
     */
    public function delete(string $uuid): Model|null
    {
        return ConcernsTransaction::delete($this->user, $this->repository)
            ->delete($uuid);
    }

    /**
     * Update status
     * @param PostingStatus $status
     * @param string $uuid
     * @return Model|null
     */
    public function updateStatus(PostingStatus $status, string $uuid): Model|null
    {
        return (new StatusUpdatable($this->user, $this->repository))
            ->update($status, $uuid);
    }
}