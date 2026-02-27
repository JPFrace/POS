<?php

namespace App\Models;

use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetType extends Model
{
    use HasFactory;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $table = 'budget_types';

    protected $fillable = [
        'name',
        'description',
        'is_inactive',
        'creator_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:mdY',
        'updated_at' => 'datetime:mdY',
        'is_inactive' => 'boolean',
    ];

    protected $hidden = [
        'id',
        'creator_id',
    ];

    public function chartAccounts()
    {
        return $this->hasMany(ChartAccount::class, 'budget_type_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
