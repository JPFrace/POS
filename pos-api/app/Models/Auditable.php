<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Auditable extends Model implements AuditableContract
{
    use AuditableTrait;

    protected $auditTimestamps = true;

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];

    public function auditable(): MorphTo
    {
        return $this->morphTo()->withTrashed();
    }

    public function user(): MorphTo
    {
        return $this->morphTo()->withTrashed();
    }
}
