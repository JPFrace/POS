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

class Page extends Model
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
        'icon',
        'url',
        'page_id',
        'sort'
    ];

    protected $hidden = [
        'id',
        'page_id'
    ];

    protected $casts = [
        'created_at' => 'datetime: m/d/Y H:i:s',
    ];

    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'page_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}
