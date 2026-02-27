<?php

namespace App\Models;

use App\Supports\Models\Common;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Action extends Model
{
    use Common;
    use HasFactory;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $fillable = [
        'name',
        'identifier',
        'policy_id'
    ];

    protected $hidden = [
        'id',
        'policy_id'
    ];

    protected $casts = [
        'created_at' => 'datetime: m/d/Y H:i:s',
    ];

    public static function booted()
    {
        static::created(function (Action $model) {
            $model->identifier = str($model->name)->slug();
            $model->save();
        });
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class, 'action_id');
    }

    public function policy()
    {
        return $this->belongsTo(Policy::class)->with('parent');
    }
}
