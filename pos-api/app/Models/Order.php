<?php

namespace App\Models;

use App\Enums\PoStatus;
use App\Enums\TransType;
use App\Models\Concerns\OrderCalculate;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    use OrderCalculate;

    protected $fillable = [
        'order_no',
        'date',
        'remarks',
        'file_id',
        'creator_id',
        'vendor_idno',
        'vendor_name',
        'vendor_email',
        'billing_address',
        'payment_method_id',
        'amount',
        'status'
    ];

    protected $casts = [
        'date' => 'datetime:m/d/Y',
        'amount' => 'decimal:2',
        'status' => PoStatus::class
    ];

    protected $hidden = [
        'id',
        'file_id',
        'creator_id',
        'payment_method_id',
        'deleted_at',
    ];

    public static function booted()
    {
        static::creating(function (Order $model) {
            if (empty($model->order_no)) {
                $model->order_no = (Order::latest('id')->first()?->id ?? 0) + 1;
            }
        });
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(TransType::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Contact::class, 'vendor_idno', 'id_no');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function delivered(): HasMany
    {
        return $this->hasMany(BillDetail::class, 'order_id');
    }

    public function total(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->details->reduce(function ($sum, $item) {
                $sum += $item->sub_total;

                return $sum;
            }, 0)
        );
    }
}
