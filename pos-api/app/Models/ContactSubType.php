<?php

namespace App\Models;

use App\Enums\ContactType;
use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactSubType extends Auditable
{
    use HasFactory;
    use SoftDeletes;
    use RouteModelBinding;

    protected $fillable = [
        'name'
    ];
    protected $hidden = [
        'created_by',
        'deleted_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y'
    ];
}