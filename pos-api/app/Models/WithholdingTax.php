<?php

namespace App\Models;

use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class WithholdingTax extends Model
{
    use HasFactory;
    use RouteModelBinding;
    use SoftDeletes;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }
    protected $table = 'withholding_taxes';

    protected $fillable = [
        'code',
        'description',
        'rate',
        'type_id',
        'payer_type_id',
        'is_inactive',
        'created_by',
    ];
    protected $casts = [
        'created_at' => 'datetime:m/d/Y',
        'is_inactive' => 'boolean',
        'rate' => 'integer',
    ];
    protected $hidden = [
        'id',
    ];
    public function taxType()
    {
        return $this->belongsTo(WithholdingTaxType::class, 'type_id');
    }

    public function payerType()
    {
        return $this->belongsTo(ContactSubType::class, 'payer_type_id');
    }
}