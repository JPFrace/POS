<?php

namespace App\Models;

use App\Supports\Models\Common;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryType extends Model
{
    use Common;
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
        'active'
    ];

    protected $hidden = [
        'id'
    ];

    protected $casts = [
        'created_at' => 'datetime: m/d/Y H:i:s',
        'active' => 'boolean'
    ];

    public function paymentMethods()
    {
        return $this->belongsToMany(PaymentMethod::class, "delivery_methods");
    }

    public function deliveryMethods()
    {
        return $this->hasMany(DeliveryMethod::class);
    }
}
