<?php

namespace App\Models;

use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BudgetDetail extends Auditable
{
    use HasFactory;

    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $table = 'budget_details';
    protected $fillable = [
        'budget_id',
        'chart_account_id',
        'product_id',
        'name',
        'quantity',
        'amount',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime:mdY',
        'updated_at' => 'datetime:mdY',
    ];

    protected $hidden = [
        'id',
        'budget_id',
        'chart_account_id',
        'product_id',
    ];

    protected static function booted()
    {
        static::saved(function (BudgetDetail $model) {

            if (bccomp($model->amount, '0', 2) === 0) {
                $model->periods()->delete();
                return;
            }

            $budget = $model->amount;

            $base = bcdiv($budget, 12, 2);
            $allocated = bcmul($base, 11, 2);
            $lastAmount = bcsub($budget, $allocated, 2);

            $periods = [];

            for ($i = 1; $i <= 11; $i++) {
                $periods["period_$i"] = $base;
            }

            $periods['period_12'] = $lastAmount;

            $model->periods()->updateOrCreate(
                ['budget_detail_id' => $model->id],
                $periods
            );
        });
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class, 'budget_id');
    }

    public function periods()
    {
        return $this->hasMany(BudgetDetailPeriod::class, 'budget_detail_id');
    }

    public function chartAccount()
    {
        return $this->belongsTo(ChartAccount::class, 'chart_account_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
