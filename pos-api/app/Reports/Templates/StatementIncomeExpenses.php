<?php

namespace App\Reports\Templates;

use App\Enums\AccountCategory;
use App\Enums\AccountUsageType;
use App\Reports\LedgerQuery;

class StatementIncomeExpenses
{
    public function __construct(protected array $dates)
    {

    }

    public static function make(array $dates)
    {
        return (new self($dates))->handle();
    }

    protected function handle()
    {
        $income = LedgerQuery::make(
            $this->dates,
            function ($query) {
                return $query->where('account_classes.name', 'Educational Income');
            },
            function ($query) {
                return $query->whereRelation('type.category', 'name', AccountCategory::REVENUE->value);
            },
        );
        \Log::info($income->toArray());
        $discounts = LedgerQuery::make(
            $this->dates,
            function ($query) {
                return $query->where('account_classes.name', 'Educational Income');
            },
            function ($query) {
                return $query->whereRelation('parent', 'name', 'Scholarships and Discounts');
            },
        );
        $educational_expenses = LedgerQuery::make(
            $this->dates,
            function ($query) {
                return $query->where('account_classes.name', 'Educational Expenses');
            },
        );
        $administrative_expenses = LedgerQuery::make(
            $this->dates,
            function ($query) {
                return $query->where('account_classes.name', 'Administrative Expenses');
            },
        );

        $other_related_educ_income = LedgerQuery::make(
            $this->dates,
            function ($query) {
                return $query->where('account_classes.name', 'Educational Related Income');
            },
        );

        $reports['educational_income'] = $this->income($income);
        $reports['total_income'] = $this->totalIncome($income);
        $reports['total_income_discounts'] = $this->totalIncomeDiscounts($discounts);
        $reports['net_income'] = $this->netIncome($reports['total_income'], $reports['total_income_discounts']);

        $reports['educational_expenses'] = $this->expenses($educational_expenses);
        $reports['total_educational_expenses'] = $this->totalExpenses($educational_expenses);

        $reports['administrative_expenses'] = $this->expenses($administrative_expenses);
        $reports['total_administrative_expenses'] = $this->totalExpenses($administrative_expenses);

        $reports['total_educational_administrative_expenses'] = $this->totalCombineExpenses(
            $reports['total_educational_expenses'],
            $reports['total_administrative_expenses']
        );

        $reports['excess_income_over_expenses'] = $this->excesssIncomeOverExpenses($reports['net_income'], $reports['total_educational_administrative_expenses']);

        $reports['educational_related_income'] = $this->income($other_related_educ_income);
        $reports['total_educational_related_income'] = $this->totalIncome($other_related_educ_income);

        $reports['net_income_loss'] = $this->netIncomeLoss($reports);

        return $reports;
    }

    protected function income($income)
    {
        return $income->map(function ($row) {
            $row['children'] = $row['children']->groupBy(function (array $item, $key) {
                return $item['parent']['name'] ?? 'no-parent';
            });

            $row['children'] = $row['children']->map(function ($items) {
                $row['parent'] = $items->first()['parent'];
                $row['name'] = $row['parent']['name'] ?? null;
                $row['budget'] = $items->first()['budget'];
                $row['current_month'] = $items->sum('current_month_credits');
                $row['year_to_date'] = $items->sum('year_to_date_credits');
                $row['budget_to_date'] = $row['budget'] / 12;

                return $row;
            });

            $row['children'] = $row['children']->filter(fn($query) => !empty($query['parent']));
            $row['children'] = $row['children']->values();

            return $row;
        });
    }

    protected function expenses($expenses)
    {
        return $expenses->map(function ($row) {
            $row['children'] = $row['children']->groupBy(function (array $item, $key) {
                return $item['parent']['name'] ?? 'no-parent';
            });

            $row['children'] = $row['children']->map(function ($items) {
                $row['parent'] = $items->first()['parent'];
                $row['name'] = $row['parent']['name'] ?? null;
                $row['budget'] = $items->first()['budget'];
                $row['current_month'] = $items->sum('current_month_debits');
                $row['year_to_date'] = $items->sum('year_to_date_debits');
                $row['budget_to_date'] = $row['budget'] / 12;

                return $row;
            });

            $row['children'] = $row['children']->filter(fn($query) => !empty($query['parent']));
            $row['children'] = $row['children']->values();

            return $row;
        });
    }

    protected function netIncome($totalIncome, $totalDiscounts)
    {
        return [
            'name' => "Net Education Income",
            'beginning_balance' => $totalIncome['beginning_balance'] - $totalDiscounts['beginning_balance'],
            'budget' => $totalIncome['budget'] - $totalDiscounts['budget'],
            'current_month' => $totalIncome['current_month'] - $totalDiscounts['current_month'],
            'year_to_date' => $totalIncome['year_to_date'] - $totalDiscounts['year_to_date'],
            'budget_to_date' => $totalIncome['budget_to_date'] + $totalDiscounts['budget_to_date']
        ];
    }

    protected function totalIncome($income)
    {
        return [
            'name' => "Total " . (((object) $income->first())?->name ?? 'None'),
            'beginning_balance' => $income->reduce(function ($sum, $row) {
                return $sum += $row['beginning_balance'];
            }, 0),
            'budget' => $income->reduce(function ($sum, $row) {
                return $sum += $row['children']->sum('budget');
            }, 0),
            'current_month' => $income->reduce(function ($sum, $row) {
                return $sum += $row['children']->sum('current_month_credits');
            }, 0),
            'year_to_date' => $income->reduce(function ($sum, $row) {
                return $sum += $row['children']->sum('year_to_date_credits');
            }, 0),
            'budget_to_date' => $income->reduce(function ($sum, $parent) {
                return $sum += $parent['children']->sum('budget');
            }, 0)
        ];
    }

    protected function totalIncomeDiscounts($discounts)
    {
        return [
            'name' => 'Less: Scholarships and Discounts',
            'beginning_balance' => $discounts->reduce(function ($sum, $row) {
                return $sum += $row['children']->sum('balance');
            }, 0),
            'budget' => $discounts->reduce(function ($sum, $row) {
                return $sum += $row['children']->sum('budget');
            }, 0),
            'current_month' => $discounts->reduce(function ($sum, $row) {
                return $sum += $row['children']->sum('current_month_credits');
            }, 0),
            'year_to_date' => $discounts->reduce(function ($sum, $row) {
                return $sum += $row['children']->sum('year_to_date_credits');
            }, 0),
            'budget_to_date' => $discounts->reduce(function ($sum, $parent) {
                return $sum += $parent['children']->sum('budget');
            }, 0)
        ];
    }

    protected function totalExpenses($expenses)
    {
        return [
            'name' => "Total " . (((object) $expenses->first())?->name ?? 'None'),
            'beginning_balance' => $expenses->reduce(function ($sum, $row) {
                return $sum += $row['beginning_balance'];
            }, 0),
            'budget' => $expenses->reduce(function ($sum, $row) {
                return $sum += $row['children']->sum('budget');
            }, 0),
            'current_month' => $expenses->reduce(function ($sum, $row) {
                return $sum += $row['children']->sum('current_month_debits');
            }, 0),
            'year_to_date' => $expenses->reduce(function ($sum, $row) {
                return $sum += $row['children']->sum('year_to_date_debits');
            }, 0),
            'budget_to_date' => $expenses->reduce(function ($sum, $parent) {
                return $sum += $parent['children']->sum('budget');
            }, 0)
        ];
    }

    protected function totalCombineExpenses($expense1, $expense2)
    {
        return [
            'name' => "Total Educ & Admin Expenses",
            'beginning_balance' => $expense1['beginning_balance'] + $expense2['beginning_balance'],
            'budget' => $expense1['budget'] + $expense2['budget'],
            'current_month' => $expense1['current_month'] + $expense2['current_month'],
            'year_to_date' => $expense1['year_to_date'] + $expense2['year_to_date'],
            'budget_to_date' => $expense1['budget_to_date'] + $expense2['budget_to_date'],
        ];
    }

    protected function excesssIncomeOverExpenses($income, $expense)
    {
        return [
            'name' => "Excess of Income Over Expenses",
            'beginning_balance' => $income['beginning_balance'] - $expense['beginning_balance'],
            'budget' => $income['budget'] + $expense['budget'],
            'current_month' => $income['current_month'] - $expense['current_month'],
            'year_to_date' => $income['year_to_date'] - $expense['year_to_date'],
            'budget_to_date' => $income['budget_to_date'] + $expense['budget_to_date'],
        ];
    }


    protected function totalOtherIncome($expenses)
    {
        return [
            'beginning_balance' => $expenses->reduce(function ($sum, $row) {
                return $sum += $row['beginning_balance'];
            }, 0),
            'current_month' => $expenses->reduce(function ($sum, $row) {
                return $sum += array_reduce($row['children'], function ($sum, $row) {
                    return $sum += $row['current_month_debits'];
                }, 0);
            }, 0),
            'year_to_date' => $expenses->reduce(function ($sum, $row) {
                return $sum += array_reduce($row['children'], function ($sum, $row) {
                    return $sum += $row['year_to_date_debits'];
                }, 0);
            }, 0),
            'budget_to_date' => $expenses->reduce(function ($sum, $parent) {
                return $sum += $parent['beginning_balance'] - array_reduce($parent['children'], function ($sum, $row) use ($parent) {
                    return $sum += $row['year_to_date_debits'];
                }, 0);
            }, 0)
        ];
    }

    protected function netIncomeLoss($reports)
    {
        return [
            'name' => "Net Income (Loss)",
            'beginning_balance' => (function () use ($reports) {
                $totals = [
                    $reports['net_income']['beginning_balance'],
                    $reports['total_educational_related_income']['beginning_balance'],
                ];
                return array_sum($totals) - $reports['total_educational_administrative_expenses']['beginning_balance'];
            })(),
            'budget' => (function () use ($reports) {
                $totals1 = [
                    $reports['net_income']['budget'],
                ];
                $totals2 = [
                    $reports['total_educational_expenses']['budget'],
                    $reports['total_administrative_expenses']['budget'],
                    $reports['excess_income_over_expenses']['budget'],
                ];
                $totals3 = [
                    $reports['total_educational_related_income']['budget'],
                ];
                return (array_sum($totals1) - array_sum($totals2)) + array_sum($totals3);
            })(),
            'current_month' => (function () use ($reports) {
                $totals = [
                    $reports['net_income']['current_month'] - $reports['total_educational_administrative_expenses']['current_month'],
                    $reports['total_educational_related_income']['current_month'],
                ];
                return array_sum($totals);
            })(),
            'year_to_date' => (function () use ($reports) {
                $totals = [
                    $reports['net_income']['year_to_date'] - $reports['total_educational_administrative_expenses']['year_to_date'],
                    $reports['total_educational_related_income']['year_to_date'],
                ];
                return array_sum($totals);
            })(),
            'budget_to_date' => (function () use ($reports) {
                $totals = [
                    $reports['net_income']['budget_to_date'] - $reports['total_educational_administrative_expenses']['budget_to_date'],
                    $reports['total_educational_related_income']['budget_to_date'],
                ];
                return array_sum($totals);
            })()
        ];
    }
}