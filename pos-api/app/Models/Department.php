<?php

namespace App\Models;

use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Auditable
{
    use HasFactory;
    use SoftDeletes;

    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $fillable = [
        'name',
        'description',
        'code',
        'created_by',
        'is_inactive',
    ];
    protected $casts = [
        'is_inactive' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];
    protected $hidden = [
        'updated_at',
        'id'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'department', 'id');
    }
}