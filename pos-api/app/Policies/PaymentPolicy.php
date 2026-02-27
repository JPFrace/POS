<?php

namespace App\Policies;

use App\Enums\PaymentStatusEnum;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaymentPolicy
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
    public function view(User $user, Payment $payment): bool
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
    public function update(User $user, Payment $payment): Response
    {
        return $payment->status_id !== PaymentStatusEnum::PAID
            ? Response::allow()
            : Response::deny('Cannot update a posted payment.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Payment $payment): Response
    {
        return $payment->status_id !== PaymentStatusEnum::PAID
            ? Response::allow()
            : Response::deny('Cannot delete a posted payment.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Payment $payment): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Payment $payment): bool
    {
        //
    }
}
