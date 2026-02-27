<?php

namespace App\Models;

use App\Supports\Utils\Amount;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }


    protected $fillable = [
        'order_id',
        'product_id',
        'product_expense_id',
        'quantity',
        'rate',
        'product_name',
        'product_description',
        'delivered'
    ];

    protected $casts = [
        'rate' => 'decimal:4',
    ];

    protected $hidden = [
        'id',
        'product_id',
        'order_id',
        'product_expense_id',
        'deleted_at',
    ];

    protected $appends = [
        'sub_total'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function rate(): Attribute
    {
        return Attribute::make(
            get: fn() => Amount::acceptable($this->attributes['rate']),
            set: fn($value) => Amount::acceptable($value)
        );
    }


    public function quantity(): Attribute
    {
        return Attribute::make(
            get: fn() => Amount::acceptable($this->attributes['quantity']),
            set: fn($value) => Amount::acceptable($value)
        );
    }

    public function productDescribe(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->product_name . "," . $this->product_description,
        );
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function subTotal(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->quantity * $this->rate
        );
    }
}
