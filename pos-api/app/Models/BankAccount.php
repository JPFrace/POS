<?php

namespace App\Models;

use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BankAccount extends Model
{
    use HasFactory;

    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }
    protected $fillable = [
        'account_number',
        'account_name',
        'bank_name',
        'bank_code',
        'account_id',
        'is_inactive'
    ];
    protected $casts = [
        'is_inactive' => 'boolean',
        'created_at' => 'datetime:m/d/Y',
        'updated_at' => 'datetime:m/d/Y',
    ];
    protected $hidden = [
        'id',
        'account_id'
    ];

    public function chartAccount(): BelongsTo
    {
        return $this->belongsTo(ChartAccount::class, 'account_id');
    }
}
