<?php

namespace App\Models;

use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logger extends Model
{
    use HasFactory;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $table = 'logger';

    protected $fillable = [
        'page',
        'action',
        'path',
        'method',
        'uri',
        'reference',
        'request',
        'creator_id',
        'ip_address',
        'device'
    ];

    protected $casts = [
        'reference' => 'array',
        'request' => 'array',
    ];
}
