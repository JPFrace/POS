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

class Policy extends Model
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
        'policy_id'
    ];

    protected $hidden = [
        'id',
        'policy_id',
    ];

    protected $casts = [
        'created_at' => 'datetime: m/d/Y H:i:s',
    ];

    public function actions(): HasMany
    {
        return $this->hasMany(Action::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Policy::class);
    }

    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Policy::class, 'policy_id');
    }
}
