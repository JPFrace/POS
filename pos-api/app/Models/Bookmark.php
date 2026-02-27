<?php

namespace App\Models;

use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bookmark extends Model
{
    use HasFactory;
    use SoftDeletes;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $fillable = [
        'date_from',
        'date_to',
        'report_id',
        'user_id',
        'name',
        'group',
    ];

    protected $casts = [
        'date_from' => 'datetime:Y-m-d',
        'date_to' => 'datetime:Y-m-d',
        'created_at' => 'datetime:m/d/Y',
    ];

    protected $hidden = [
        'id',
        'report_id',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'report_details',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reportDetails(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->report()->first()
        );
    }
}
