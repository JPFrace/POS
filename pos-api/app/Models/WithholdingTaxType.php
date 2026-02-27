<?php

namespace App\Models;

use App\Supports\Models\Common;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Model;

class WithholdingTaxType extends Model
{
    use HasFactory;
    use RouteModelBinding;
    use Common;

    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }
    protected $table = 'withholding_tax_types';

    protected $fillable = [
        'code',
        'name',
        'description',
    ];
    protected $casts = [
        'created_at' => 'datetime:d/m/Y',
    ];
    protected $hidden = [
        'id',
    ];
}