<?php

namespace App\Models;

use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountClass extends Model
{
    use SoftDeletes;
    use HasFactory;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }
    protected $table = 'account_classes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'short_name',
        'description',
        'is_inactive'
    ];

    protected $casts = [
        'is_inactive' => 'boolean',
        'created_at' => 'datetime:d/m/Y',
    ];

    protected $hidden = [
        'id'
    ];

    public function chartAccounts()
    {
        return $this->hasMany(ChartAccount::class, 'class_id');
    }

    public function tax()
    {
        return $this->hasMany(Tax::class, 'class_id');
    }
}
