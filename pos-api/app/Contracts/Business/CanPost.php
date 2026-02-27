<?php

namespace App\Contracts\Business;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

interface CanPost
{
    /**
     * Check if posted
     * @return bool
     */
    public function isPosted(): bool;

    /**
     * Post
     * @param \App\Models\User $user
     * @param Carbon $date
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function post(User $user, Carbon $date): Model;

    /**
     * Unpost
     * @param \App\Models\User $user
     * @param Carbon $date
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function unpost(User $user, Carbon $date): Model;

}