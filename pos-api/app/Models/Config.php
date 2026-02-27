<?php

namespace App\Models;

use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $table = 'configs';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'value',
        'options', // { value: 1, label: "Draft" }, { value: 2, label: "Pending" }, { value: 3, label: "For Approval" }, { value: 4, label: "Approved" }, { value: 5, label: "Posted" }
        'parent_id',
        'prefix',
        'use_prefix',
        'suffix',
        'use_suffix',
        'seq',
        'is_inactive',
    ];

    protected $hidden = [
        'id',
        'parent_id'
    ];

    public function scopeActive(&$query)
    {
        return $query->where('is_inactive', 0);
    }

    public function parent()
    {
        return $this->belongsTo(Config::class);
    }

    public function children()
    {
        return $this->hasMany(Config::class, 'parent_id')
            ->with('children')->where('is_inactive', 0)->orderBy('seq');
    }
}