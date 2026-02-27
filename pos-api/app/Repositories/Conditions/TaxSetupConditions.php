<?php

namespace App\Repositories\Conditions;


trait TaxSetupConditions
{
    public function calendarCondition(&$builder, $query)
    {
        return $builder->when($query['calendar'], function ($builder) use ($query) {
            $builder->with('calendar');
        });
    }
    public function taxCondition(&$builder, $query)
    {
        return $builder->when($query['tax'], function ($builder) use ($query) {
            $builder->with(['tax']);
        });
    }

}
