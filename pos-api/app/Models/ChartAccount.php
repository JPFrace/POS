<?php

namespace App\Models;

use App\Enums\AccountUsageType as EnumAccountUsageType;
use App\Supports\Models\RouteModelBinding;
use App\Supports\Utils\Amount;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Supports\Models\HasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChartAccount extends Model
{
    use SoftDeletes;
    use HasFactory;
    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $fillable = [
        'code',
        'name',
        'description',
        'class_id',
        'type_id',
        'balance',
        'parent_id',
        'dept_id',
        'balance',
        'running_balance',
        'usage_type_id',
        'budget',
        'run_balance_at',
        'start_track_balance_at'
    ];

    protected $hidden = [
        'id',
        'type_id',
        'usage_type_id',
        'class_id',
        'parent_id',
        'dept_id'
    ];


    protected $casts = [
        'is_inactive' => 'boolean',
        'created_at' => 'datetime:d/m/Y',
    ];

    protected $appends = [
        'url'
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            $model->balance = Amount::acceptable($model->balance);
            $model->running_balance = Amount::acceptable($model->running_balance);
            $model->balance = preg_replace('/[^0-9.]/', '', $model->balance);
        });
    }

    public function scopeUsage($query, $usage)
    {
        return $query->whereRelation('usageType', 'name', $usage);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(AccountType::class, 'type_id');
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(AccountClass::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    public function usageType(): BelongsTo
    {
        return $this->belongsTo(AccountUsageType::class, 'usage_type_id');
    }

    public function children()
    {
        return $this->hasMany(ChartAccount::class, 'parent_id')->with([
            'children.type.category',
            'children.department',
            'children.class',
            'children.parent',
            'parent'
        ]);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ChartAccount::class);
    }

    public function scopeAccountPayable(Builder $query, $name = 'Accounts Payable')
    {
        return $query->whereRelation('type', 'name', 'like', "%$name%");
    }

    public function scopeAccountsReceivable(Builder $query, $name = 'Accounts Receivable A/R')
    {
        return $query->usage($name);
    }

    public function scopeVatable(Builder $query, $name = 'vat')
    {
        return $query->usage($name);
    }

    public function isCashInBank(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->where('id', $this->id)->usage('Cash In Bank')->first() ? true : false
        );
    }

    public function journals(): HasMany
    {
        return $this->hasMany(Journal::class, 'chart_account_id');
    }

    public function credits(): HasMany
    {
        return $this->journals()->where('credit', '>', 0);
    }

    public function debits(): HasMany
    {
        return $this->journals()->where('debit', '>', 0);
    }

    public function sumCredits(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->credits()->sum('credit')
        );
    }

    public function sumDebits(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->debits()->sum('debit')
        );
    }

    public function balances(): HasMany
    {
        return $this->hasMany(ChartAccountBalance::class);
    }

    public function scopeBalancesAt($query, Carbon $start, Carbon $end): HasMany
    {
        return $this->balances()
            ->where('start_at', '>=', $start->format('Y-m-d'))
            ->where('end_at', '>=', $end->format('Y-m-d'));
    }

    public function bank(): HasOne
    {
        return $this->hasOne(BankAccount::class, 'account_id');
    }
    public function url(): Attribute
    {
        return Attribute::make(
            get: fn() => "/transactions/{$this->uuid}"
        );
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
