<?php
namespace App\Repositories;

use App\Models\Audit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuditRepository extends Repository
{
    use Conditions\AuditConditions;
    public function __construct(protected Audit $model)
    {

    }

    public function getActivities(User $user)
    {
        return $user->audits()
            ->orderByDesc('id')
            ->limit(10)
            ->get();
    }
}
