<?php

namespace App\Repositories\Conditions;

use Illuminate\Database\Eloquent\Builder;



trait ReportSignatoryCondition
{
    public function reportSignatoryCondition(&$builder, $query)
    {
        return $builder->when($query['report_signatory'], function (Builder $builder) use ($query) {
            $builder->orwhere('label', 'like', '%' . $query['report_signatory'] . '%')
                ->orWhereRelation('signatory', 'name', 'like', '%' . $query['report_signatory'] . '%')
                ->orWhereRelation('report', 'name', 'like', '%' . $query['report_signatory'] . '%');
        });
    }
    public function reportCondition(&$builder, $query)
    {
        return $builder->when($query['report'], function ($builder) use ($query) {
            $builder->with(['report']);
        });
    }
    public function signatoryCondition(&$builder, $query)
    {
        return $builder->when($query['signatory'], function ($builder) use ($query) {
            $builder->with(['signatory']); //should exist in the model
        });
    }
}
