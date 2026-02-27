<?php

namespace App\Models;

use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use OwenIt\Auditing\Models\Audit as BaseAudit;
use OwenIt\Auditing\Contracts\Audit as AuditContract;

class Audit extends BaseAudit implements AuditContract
{
    use HasFactory;

    protected $fillable = [
        'user_type',
        'user_id',
        'event',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
        'tags'
    ];

    protected $casts = [
        'old_values'   => 'json',
        'new_values'   => 'json',
        'auditable_id' => 'integer',
        'user_id'      => 'integer',
        'tags'         => 'json',
        'created_at' => 'datetime:d/m/Y-H:i:s',
        'updated_at' => 'datetime:d/m/Y-H:i:s',
    ];

    protected $hidden = [
        'user_id',
        'auditable_id',
    ];
    public function auditable()
    {
        return $this->morphTo()->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userType(): Attribute
    {
        return Attribute::make(get: fn(): mixed => $this->user->name ?? 'N/A');
    }

    public function auditableType(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $auditable_type = explode('\\', $value);
                return $auditable_type ? end($auditable_type) : 'N/A';
            }
        );
    }
}
