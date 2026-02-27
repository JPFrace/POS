<?php

namespace App\Models\Concerns;

use App\Models\Taxonomy;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

trait CanPost
{
    /**
     * @inheritDoc
     */
    public function post(User $user, Carbon $date): Model
    {
        $this->status()->associate(Taxonomy::transactionPosted()->first());
        $this->posted_at = $date;
        $this->postedBy()->associate($user);
        $this->save();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function unpost(User $user, Carbon $date): Model
    {
        $this->status()->associate(Taxonomy::transactionUnposted()->first());
        $this->posted_at = null;
        $this->postedBy()->dissociate();

        $this->unposted_at = $date;
        $this->unpostedBy()->associate($user);
        $this->save();

        return $this;
    }
}