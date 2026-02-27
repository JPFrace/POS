<?php

namespace App\Models;

use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetDetailPeriod extends Auditable
{
    use HasFactory;

    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $table = 'budget_detail_periods';

    protected $fillable = [
        'budget_detail_id',
        'period_1',
        'period_2',
        'period_3',
        'period_4',
        'period_5',
        'period_6',
        'period_7',
        'period_8',
        'period_9',
        'period_10',
        'period_11',
        'period_12',
    ];

    protected $casts = [
        'period_1' => 'decimal:2',
        'period_2' => 'decimal:2',
        'period_3' => 'decimal:2',
        'period_4' => 'decimal:2',
        'period_5' => 'decimal:2',
        'period_6' => 'decimal:2',
        'period_7' => 'decimal:2',
        'period_8' => 'decimal:2',
        'period_9' => 'decimal:2',
        'period_10' => 'decimal:2',
        'period_11' => 'decimal:2',
        'period_12' => 'decimal:2',
    ];

    protected $hidden = [
        'id',
        'budget_detail_id',
    ];

    public function budgetDetail()
    {
        return $this->belongsTo(BudgetDetail::class, 'budget_detail_id');
    }
}
