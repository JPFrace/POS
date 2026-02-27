<?php

namespace App\Models;

use App\Supports\Models\HasUuid;
use App\Supports\Models\RouteModelBinding;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Calendar extends Auditable
{
    use HasFactory;

    use RouteModelBinding;
    use HasUuid, HasUuids {
        HasUuid::newUniqueId insteadof HasUuids;
        HasUuid::uniqueIds insteadof HasUuids;
    }

    protected $table = 'calendars';
    protected $fillable = [
        'year',
        'no_of_periods',
        'start_date',
        'end_date',
        'period_1',
        'period_1_closed',
        'period_2',
        'period_2_closed',
        'period_3',
        'period_3_closed',
        'period_4',
        'period_4_closed',
        'period_5',
        'period_5_closed',
        'period_6',
        'period_6_closed',
        'period_7',
        'period_7_closed',
        'period_8',
        'period_8_closed',
        'period_9',
        'period_9_closed',
        'period_10',
        'period_10_closed',
        'period_11',
        'period_11_closed',
        'period_12',
        'period_12_closed',
        'created_by',
        'is_inactive',
    ];
    protected $casts = [
        'period_1_closed' => 'boolean',
        'period_2_closed' => 'boolean',
        'period_3_closed' => 'boolean',
        'period_4_closed' => 'boolean',
        'period_5_closed' => 'boolean',
        'period_6_closed' => 'boolean',
        'period_7_closed' => 'boolean',
        'period_8_closed' => 'boolean',
        'period_9_closed' => 'boolean',
        'period_10_closed' => 'boolean',
        'period_11_closed' => 'boolean',
        'period_12_closed' => 'boolean',
        'is_inactive' => 'boolean',
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
        'created_at' => 'datetime:m/d/Y',
    ];
    protected $hidden = [
        'id',
        'updated_at',
    ];

    /**
     * Scope: find calendars where a given date falls within start_date and end_date.
     */
    public function scopeForDate($query, Carbon $date)
    {
        return $query->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date);
    }
    /**
     * Get full details of the period a date falls into.
     */
    public function getPeriod(Carbon $date): ?array
    {
        $start = $this->start_date;

        for ($i = 1; $i <= 12; $i++) {
            $end = $this->{"period_{$i}"};

            if ($date->between($start, $end)) {
                return [
                    'year' => $this->year,
                    'number' => $i,
                    'start' => $start instanceof Carbon ? $start : Carbon::parse($start),
                    'end' => $end instanceof Carbon ? $end : Carbon::parse($end),
                    'closed' => (bool) $this->{"period_{$i}_closed"},
                ];
            }

            // Next period starts the day after this period ends
            $start = Carbon::parse($end)->addDay();
        }

        return null;
    }

    /**
     * Check if a given date is inside a closed fiscal period.
     */
    public static function checkClosedPeriod(Carbon $date): array|null
    {
        $calendar = static::forDate($date)->first();

        if (!$calendar) {
            return null;
        }

        $period = $calendar->getPeriod($date);

        return $period ? $period : null;
    }

    /**
     * Check if the date is calendar closed
     * @param Carbon $date
     * @return bool
     */
    public static function isClosed(Carbon $date): bool
    {
        return (new self)->checkClosedPeriod($date)['closed'] ?? true;
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class, 'calendar_id', 'id');
    }

    public function hasConstraints(): bool
    {
        return $this->budgets()->exists();
    }
}
