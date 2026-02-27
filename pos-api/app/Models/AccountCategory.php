<?php

namespace App\Models;

use App\Enums\NormalBalance;
use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountCategory extends Model
{
    // use SoftDeletes;
    use HasFactory;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }
    protected $fillable = [
        'name',
        'description',
        'is_inactive',
        'seq',
        'normal_balance'
    ];

    protected $casts = [
        'is_inactive' => 'boolean',
        'created_at' => 'datetime:d/m/Y',
        'normal_balance' => NormalBalance::class
    ];

    protected $hidden = [
        'id'
    ];

    public function type(): HasMany
    {
        return $this->hasMany(AccountType::class);
    }
}
