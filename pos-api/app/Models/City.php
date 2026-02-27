<?php

namespace App\Models;

use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }
    protected $fillable = ['name', 'region_id'];

    protected $hidden = [
        'id'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get the barangays for the city.
     */
    public function barangays()
    {
        return $this->hasMany(Barangay::class);
    }

    /**
     * (Optional) Get the region through the province.
     */
    public function region()
    {
        return $this->hasOneThrough(Region::class, Province::class);
    }
}
