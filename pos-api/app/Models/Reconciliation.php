<?php

namespace App\Models;

use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

use function PHPSTORM_META\map;

class Reconciliation extends Model
{
    use HasFactory;
    use SoftDeletes;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $fillable = [
        'start_at',
        'end_at',
        'bank_statement_ending_balance',
        'ending_balance',
        'account_id',
        'calendar_id',
        'closed_at',
        'closed_by',
    ];

    protected $hidden = [
        'id',
        'calendar_id',
        'account_id',
        'closed_by',
        'created_at',
        'deleted_at',
        'updated_at'
    ];

    protected $casts = [
        'start_at' => 'datetime: m/d/Y H:i:s',
        'end_at' => 'datetime: m/d/Y H:i:s',
        'closed_at' => 'datetime: m/d/Y H:i:s',
        'bank_statement_ending' => 'decimal:4',
    ];

    public function cashInBank(): BelongsTo
    {
        return $this->belongsTo(ChartAccount::class, 'account_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
}
