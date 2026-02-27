<?php

namespace App\Models;
use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $hidden = [
        'id'
    ];

    protected $fillable = ['name'];

    public function provinces()
    {
        return $this->hasMany(Province::class);
    }

    /**
     * (Optional) Get all cities through provinces.
     */
    public function cities()
    {
        return $this->hasManyThrough(City::class, Province::class);
    }

    /**
     * (Optional) Get all barangays through provinces and cities.
     */
    public function barangays()
    {
        return $this->hasManyThrough(Barangay::class, City::class, 'province_id', 'city_id', 'id', 'id');
    }
}
