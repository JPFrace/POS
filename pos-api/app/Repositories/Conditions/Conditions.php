<?php

namespace App\Repositories\Conditions;

use App\Exceptions\Throws;

trait Conditions
{
    public function conditions($builder, array $query)
    {
        $keys = array_keys($query);
        foreach ($keys as $key) {
            if (preg_match('/(\w+|\W+|\w+)*\(\d\)/', $key, $matches)) {
                $key = preg_replace('/\(\d\)/', '', $matches[0]);
                preg_match('/\d/', $matches[0], $limit);

                $query[$key]['limit'] = $limit[0];
            }

            $key = str_replace('.', 'With_', $key);
            $condition = str($key)->camel() . 'Condition';

            if (!is_callable([new static($this->model), $condition])) {
                throw new Throws("Search query {$condition} is not defined.");
            }

            $builder = $this->$condition($builder, $query);
        }

        return $builder;
    }

    public function idCondition(&$builder, $query)
    {
        return $builder->when(isset($query['id']) && $query['id'], function ($builder) use ($query) {
            $builder->whereId($query['id']);
        });
    }

    public function nameCondition(&$builder, $query)
    {
        return $builder->when(isset($query['name']) && $query['name'], function ($builder) use ($query) {
            $builder->where('name', 'like', '%' . $query['name'] . '%');
        });
    }

    public function titleCondition(&$builder, $query)
    {
        return $builder->when(isset($query['title']) && $query['title'], function ($builder) use ($query) {
            $builder->where('title', 'like', '%' . $query['title'] . '%');
        });
    }

    public function activeCondition(&$builder, $query)
    {
        return $builder->when(isset($query['active']) && !is_null($query['active']), function ($builder) use ($query) {
            $builder->where($this->model()->getTable() . '.active', $query['active']);
        });
    }

    public function emailCondition(&$builder, $query)
    {
        return $builder->when($query['email'], function ($builder) use ($query) {
            $builder->where('email', 'like', '%' . $query['email'] . '%');
        });
    }

    public function regionIdCondition(&$builder, $query)
    {
        return $builder->when(isset($query['region_id']) && $query['region_id'], function ($builder) use ($query) {
            $builder->where('region_id', $query['region_id']);
        });
    }
    public function provinceIdCondition(&$builder, $query)
    {
        return $builder->when(isset($query['province_id']) && $query['province_id'], function ($builder) use ($query) {
            $builder->where('province_id', $query['province_id']);
        });
    }
    public function cityIdCondition(&$builder, $query)
    {
        return $builder->when(isset($query['city_id']) && $query['city_id'], function ($builder) use ($query) {
            $builder->where('city_id', $query['city_id']);
        });
    }

    public function uuidCondition(&$builder, $query)
    {
        return $builder->when(isset($query['uuid']) && $query['uuid'], function ($builder) use ($query) {
            $builder->where('uuid', $query['uuid']);
        });
    }

    public function shortNameCondition(&$builder, $query)
    {
        return $builder->when(isset($query['short_name']) && $query['short_name'], function ($builder) use ($query) {
            $builder->where(function ($builder) use ($query) {
                $builder->where('short_name', 'like', '%' . $query['short_name'] . '%');
            });
        });
    }

    public function nameCodeCondition(&$builder, $query)
    {
        return $builder->when(isset($query['name_code']) && $query['name_code'], function ($builder) use ($query) {
            $builder->where(function ($builder) use ($query) {
                $builder->where('name', 'like', '%' . $query['name_code'] . '%')->orWhere('code', 'like', '%' . $query['name_code'] . '%');
            });
        });
    }

    public function fileCondition(&$builder, $query)
    {
        return $builder->when($query['file'], function ($builder) use ($query) {
            $builder->with('file');
        });
    }
}