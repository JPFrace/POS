<?php

namespace App\Models;

use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfficialReceiptDenomination extends Auditable
{
    use RouteModelBinding;
    use HasFactory;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $fillable = [
        'or_id',
        'deposit_id',
        'payment_method_id',
        'quantity',
        'denomination',
        'bank',
        'reference_date',
        'reference_no',
    ];

    protected $hidden = [
        'id',
        'or_id',
        'deposit_id',
        'payment_method_id'
    ];

    protected $casts = [
        'created_at' => 'datetime:m/d/Y'
    ];

    public function officialreceipt(): BelongsTo
    {
        return $this->belongsTo(related: OfficialReceipt::class, foreignKey: 'or_id');
    }

    public function deposit_account(): BelongsTo
    {
        return $this->belongsTo(related: ChartAccount::class, foreignKey: 'deposit_id');
    }

    public function payment_method(): BelongsTo
    {
        return $this->belongsTo(related: PaymentType::class, foreignKey: 'payment_method_id');
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->quantity * $this->denomination,
        );
    }
}
