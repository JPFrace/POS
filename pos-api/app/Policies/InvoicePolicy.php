<?php

namespace App\Policies;

use App\Enums\InvoiceStatusEnum;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InvoicePolicy
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
    public function view(User $user, Invoice $invoice): bool
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
    public function update(User $user, Invoice $invoice): Response
    {
        return match ($invoice->status_id) {
            InvoiceStatusEnum::POSTED => Response::deny('Cannot update a posted invoice.'),
            InvoiceStatusEnum::PAID => Response::deny('Cannot update a paid invoice.'),
            default => Response::allow(),
        };
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Invoice $invoice): Response
    {
        return match ($invoice->status_id) {
            InvoiceStatusEnum::POSTED => Response::deny('Cannot delete a posted invoice.'),
            InvoiceStatusEnum::PAID => Response::deny('Cannot delete a paid invoice.'),
            default => Response::allow(),
        };
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Invoice $invoice): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Invoice $invoice): bool
    {
        //
    }
}
