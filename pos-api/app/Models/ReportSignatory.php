<?php

namespace App\Models;

use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportSignatory extends Model
{
    use HasFactory;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $table = 'report_signatories';
    protected $fillable = [
        'report_id',
        'label',
        'signatory_id',
        'created_by',
        'is_inactive',
        'sort',
        'year_signatory',
    ];

    protected $casts = [
        'created_at' => 'datetime:m/d/Y',
        'is_inactive' => 'boolean',
    ];

    protected $hidden = [
        'id',
        'created_by',
        'signatory_id',
        'report_id',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function signatory(): BelongsTo
    {
        return $this->belongsTo(Signatories::class, 'signatory_id');
    }

}
