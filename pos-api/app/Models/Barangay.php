<?php

namespace App\Models;
use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    use HasFactory;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }
    protected $fillable = ['name', 'city_id'];
    protected $hidden = [
        'id'
    ];
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * (Optional) Get the province through the city.
     */
    public function province()
    {
        return $this->hasOneThrough(Province::class, City::class);
    }

    /**
     * (Optional) Get the region through the province.
     */
    public function region()
    {
        return $this->hasOneThrough(Region::class, Province::class, 'id', 'id', 'province_id', 'region_id');
    }
}
