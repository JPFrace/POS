<?php

namespace App\Models;

use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCategory extends Model
{
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
        'parent_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'parent_id' => 'integer',
    ];

    protected $hidden = [
        'id',
        'parent_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id')->with([
            'children',
            'children.parent',
            'parent'
        ]);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
