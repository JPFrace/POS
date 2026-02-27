<?php

namespace App\Concerns\Transactions;

use App\Concerns\Posts;
use App\Contracts\Business\Transactable;
use App\Contracts\Supports\Repository;
use App\Enums\PostingStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class StatusUpdatable implements \App\Contracts\Transactions\StatusUpdatable
{

    protected Posts $post;

    public function __construct(protected User $user, protected Repository $repository)
    {
        $this->post = new Posts();
    }

    public function update(PostingStatus $status, string $uuid): Model|null
    {
        $transaction = $this->repository->findByUuid($uuid);

        if (!$transaction) {
            throw new \Exception("Transaction [{$uuid}] could not be found.");
        }

        if (!$transaction instanceof Transactable) {
            throw new \Exception("Transaction is not transactable.");
        }

        if ($status === PostingStatus::POSTED) {
            Posts::post($this->user, $transaction)->handle();
        } else {
            Posts::unpost($this->user, $transaction)->handle();
        }

        return $transaction;
    }
}