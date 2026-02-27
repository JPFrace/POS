<?php

namespace App\Models;
use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'region_id'];
    protected $hidden = [
        'id'
    ];
    /**
     * Get the region that owns the province.
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Get the cities for the province.
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    /**
     * Get all barangays through cities.
     */
    public function barangays()
    {
        return $this->hasManyThrough(Barangay::class, City::class);
    }
}
