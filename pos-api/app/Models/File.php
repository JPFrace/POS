<?php

namespace App\Models;

use App\Supports\Models\Common;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use Common;
    use HasFactory;
    use SoftDeletes;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $fillable = [
        'id',
        'uuid',
        'filename',
        'original_filename',
        'extension',
        'storage_path',
    ];

    protected $hidden = [
        'id'
    ];

    protected $appends = [
        'url'
    ];

    protected $casts = [
        'created_at' => 'datetime: m/d/Y H:i:s',
    ];

    public function url(): Attribute
    {
        return Attribute::make(
            get: fn() => url("storage/" . $this->storage_path)
        );
    }
}
