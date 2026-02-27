<?php

namespace App\Models;

use App\Enums\OauthType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Supports\Models\Common;
use App\Supports\Models\RouteModelBinding;


class OAuth extends Model
{
    use HasFactory;
    use SoftDeletes;

    use Common;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $table = "oauths";

    protected $fillable = [
        "oauthable",
        "type",
        "email",
        "token",
        "refresh_token",
        "expire_in"
    ];

    protected $hidden = [
        'id'
    ];

    protected $casts = [
        "type" => OauthType::class
    ];

    public function oauthable()
    {
        return $this->morphTo();
    }
}
