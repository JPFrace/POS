<?php

namespace App\Models;

use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentType extends Auditable
{
    use HasFactory;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $fillable = [
        'icon',
        'code',
        'name',
        'short_desc',
        'description',
        'inactive',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime:m/d/Y',
        'inactive' => 'boolean',
    ];

    protected $hidden = [
        'id',
        'created_by',
        'updated_at',
        'deleted_at',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}