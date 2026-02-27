<?php

namespace App\Models;

use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDimension extends Model
{
    use HasFactory;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $fillable = [
        'trans_type',
        'dimension_id'
    ];

    protected $hidden = [
        'id',
        'dimension_id',
        'transactable_id',
        'transactable_type',
        'created_at',
        'deleted_at',
        'updated_at'
    ];

    public function transactable()
    {
        return $this->morphTo();
    }

    public function dimension()
    {
        return $this->belongsTo(Dimension::class, 'dimension_id');
    }
}
