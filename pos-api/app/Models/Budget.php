<?php

namespace App\Models;

use App\Enums\BudgetStatus;
use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Budget extends Auditable
{
    use HasFactory;

    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $table = 'budgets';
    protected $fillable = [
        'name',
        'description',
        'department_id',
        'calendar_id',
        'type_id',
        'status_id',
        'is_inactive',
        'creator_id',
    ];

    protected $appends = ['status', 'budget_details'];
    protected $casts = [
        'status_id' => BudgetStatus::class,
        'created_at' => 'datetime:m/d/Y',
        'updated_at' => 'datetime:m/d/Y',
        'is_inactive' => 'boolean',
    ];

    protected $hidden = [
        'id',
        'calendar_id',
        'department_id',
        'type_id',
        'status_id',
        'creator_id',
        'details'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function calendar()
    {
        return $this->belongsTo(Calendar::class, 'calendar_id');
    }

    public function type()
    {
        return $this->belongsTo(BudgetType::class, 'type_id');
    }

    public function details()
    {
        return $this->hasMany(BudgetDetail::class, 'budget_id');
    }

    protected function budgetDetails(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->loadMissing(['details.chartAccount.type.category', 'details.periods']);
                $budgeted = $this->details->map(fn($detail) => [
                    'account'     => $detail->chartAccount,
                    'category'    => $detail->chartAccount->type->category ?? null,
                    'periods'     => $detail->periods->mapWithKeys(fn($period) => [
                        'uuid' => $period->uuid,
                        'period_1' => [
                            'amount' => (float) $period->period_1,
                            'date' => $this->calendar->period_1
                        ],
                        'period_2' => [
                            'amount' => (float) $period->period_2,
                            'date' => $this->calendar->period_2
                        ],
                        'period_3' => [
                            'amount' => (float) $period->period_3,
                            'date' => $this->calendar->period_3
                        ],
                        'period_4' => [
                            'amount' => (float) $period->period_4,
                            'date' => $this->calendar->period_4
                        ],
                        'period_5' => [
                            'amount' => (float) $period->period_5,
                            'date' => $this->calendar->period_5
                        ],
                        'period_6' => [
                            'amount' => (float) $period->period_6,
                            'date' => $this->calendar->period_6
                        ],
                        'period_7' => [
                            'amount' => (float) $period->period_7,
                            'date' => $this->calendar->period_7
                        ],
                        'period_8' => [
                            'amount' => (float) $period->period_8,
                            'date' => $this->calendar->period_8
                        ],
                        'period_9' => [
                            'amount' => (float) $period->period_9,
                            'date' => $this->calendar->period_9
                        ],
                        'period_10' => [
                            'amount' => (float) $period->period_10,
                            'date' => $this->calendar->period_10
                        ],
                        'period_11' => [
                            'amount' => (float) $period->period_11,
                            'date' => $this->calendar->period_11
                        ],
                        'period_12' => [
                            'amount' => (float) $period->period_12,
                            'date' => $this->calendar->period_12
                        ],
                    ]),
                    'amount'      => (float) $detail->amount,
                    'description' => $detail->description,
                    'is_budgeted' => true,
                ]);

                if ($this->status_id === BudgetStatus::POSTED) {
                    return $budgeted->sortBy(fn($item) => $item['category']->name ?? '')
                        ->values();
                }

                $budgetedIds = $this->details->pluck('chart_account_id');
                $unbudgeted = ChartAccount::with(['type.category'])
                    ->whereNotIn('id', $budgetedIds)
                    ->select('uuid', 'code', 'name', 'description', 'type_id')
                    ->get()
                    ->map(fn($acc) => [
                        'account'     => $acc,
                        'category'    => $acc->type->category ?? null,
                        'periods'     => [],
                        'amount'      => 0.00,
                        'description' => $acc->description,
                        'is_budgeted' => false,
                    ]);

                return $budgeted
                    ->merge($unbudgeted)
                    ->sortBy(fn($item) => $item['category']->name ?? '')
                    ->values();
            }
        );
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status_id === BudgetStatus::POSTED ? 'Posted' : 'Unposted'
        );
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    protected static function booted()
    {
        $date = Carbon::now();
        static::created(function (Budget $model) use ($date) {
            if (!$model->status_id || $model->status_id === 0) {
                if ($model) {
                    $model->status_id = BudgetStatus::UNPOSTED->value;
                    $model->save();
                }
            }
        });
    }
}
