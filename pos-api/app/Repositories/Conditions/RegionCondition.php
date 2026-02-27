<?php

namespace App\Repositories\Conditions;

trait RegionCondition
{
    public function regionCondition(&$builder, $query)
    {
        return $builder->when($query['region'], function ($builder) use ($query) {
            $builder->with('region');
        });
    }

    public function provinceCondition(&$builder, $query)
    {
        return $builder->when($query['province'], function ($builder) use ($query) {
            $builder->with('province');
        });
    }
    public function cityCondition(&$builder, $query)
    {
        return $builder->when($query['city'], function ($builder) use ($query) {
            $builder->with('city');
        });
    }

    public function barangayCondition(&$builder, $query)
    {
        return $builder->when($query['barangay'], function ($builder) use ($query) {
            $builder->with('barangay');
        });
    }
}