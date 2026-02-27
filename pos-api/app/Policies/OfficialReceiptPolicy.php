<?php

namespace App\Policies;

use App\Enums\OfficialReceiptStatusEnum;
use App\Models\OfficialReceipt;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OfficialReceiptPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OfficialReceipt $officialReceipt): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OfficialReceipt $officialReceipt): Response
    {
        return $officialReceipt->status_id !== OfficialReceiptStatusEnum::POSTED
            ? Response::allow()
            : Response::deny('Cannot update a posted official receipt.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OfficialReceipt $officialReceipt): Response
    {
        return $officialReceipt->status_id !== OfficialReceiptStatusEnum::POSTED
            ? Response::allow()
            : Response::deny('Cannot delete a posted official receipt');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OfficialReceipt $officialReceipt): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OfficialReceipt $officialReceipt): bool
    {
        //
    }
}
