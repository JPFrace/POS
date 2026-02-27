<?php

namespace App\Repositories\Conditions;

use App\Enums\ContactType;
use App\Models\Contact;

trait SignatoriesConditions
{
   public function signatoryCondition(&$builder, $query)
    {
        return $builder->when($query['signatory'], function ($builder) use ($query) {
            $builder->with('signatory');
        });
    }
    public function departmentCondition (&$builder, $query)
    {
        return $builder->when($query['department'], function ($builder) use ($query) {
            $builder->with(['department']);
        });
    }
    public function positionCondition (&$builder, $query)
    {
        return $builder->when($query['position'], function ($builder) use ($query) {
            $builder->with(['position']);
        });
    }
    public function createdByCondition(&$builder, $query)
    {
        return $builder->when($query['createdBy'], function ($builder) use ($query) {
            $builder->with(['createdBy:id,name']);
        });
    }

}
