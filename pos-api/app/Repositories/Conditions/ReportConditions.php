<?php

namespace App\Repositories\Conditions;

trait ReportConditions
{
    public function createdByCondition(&$builder, $query)
    {
        return $builder->when($query['createdBy'], function ($builder) use ($query) {
            $builder->with(['createdBy:id,name']);
        });
    }

    public function reportSignatoryCondition(&$builder, $query)
    {
        return $builder->when($query['report_signatory'], function ($builder) use ($query) {
            $builder->with(['reportSignatories']);
        });
    }

}
