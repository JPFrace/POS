<?php

namespace App\Models;

use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class External extends Model
{
    use HasFactory;
    use SoftDeletes;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $fillable = [
        'user_id',
        'trans_type',
        'remarks',
    ];

    protected $hidden = [
        'id',
        'user_id',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:m-d-Y H:i:s',
        'updated_at' => 'datetime:m-d-Y H:i:s',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(ExternalTransaction::class, 'header_id');
    }

    public function journals()
    {
        return $this->morphMany(
            Journal::class,
            'transactable'
        );
    }

    public function transactionCode()
    {
        return $this->hasOne(FinancialTransCode::class, "code", "trans_type");
    }
}
