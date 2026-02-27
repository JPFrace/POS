<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class Repository
{
    use Conditions\Conditions;

    public function model(): Model
    {
        return $this->model;
    }

    public function create(array $data): ?Model
    {
        return $this->model()->create($data);
    }

    public function update(array $data, $id, $key = 'uuid'): ?Model
    {
        $id = is_array($id) ? $id : [$id];
        $model = $this->model()->whereIn($key, $id)->first();

        foreach ($data as $key => $value) {
            $model->$key = $value;
        }

        $model->save();

        return $model;
    }

    public function updateOrInsert(array $key, array $data)
    {
        return $this->model()->updateOrInsert($key, $data);
    }


    public function delete(string|int|array $id, $key = 'uuid'): bool|null
    {
        if (is_array($id)) {
            $ids = $id;
            $rowsDeleted = 0;
            foreach ($ids as $id) {
                $rowsDeleted += $this->model()->where($key, $id)->first()?->delete();
            }
            return $rowsDeleted > 0; // Return true if any rows were deleted
        } else {
            return $this->model()->where($key, $id)->first()?->delete();
        }
    }

    public function find(int $id): ?Model
    {
        return $this->model()->find($id);
    }

    public function findBy(mixed $id, $key = 'id'): Collection
    {
        return $this->model()->newQuery()->where($key, $id)->get();
    }

    public function findByUuid(mixed $id, $key = 'uuid'): ?Model
    {
        return $this->findBy($id, $key)->first();
    }

    public function all($columns = ["*"])
    {
        return $this->model();
    }

    public function list(
        $query = [],
        $perPage = 10,
        $paginate = false,
        $first = false,
        $get = false,
        $columns = ['*'],
        $limit = null,
        array $orderBy = [],
        array $groupBy = [],
        $pageName = 'page',
        $customQuery = []

    ) {

        $builder = $this->model()->newQuery();
        if ($columns[0] != '*') {
            $builder->select($columns);
        }

        $builder = $this->conditions($builder, $query);
        if ($customQuery) {
            foreach ($customQuery as $column => $value) {
                $builder->where($column, $value);
            }
        }

        if ($orderBy) {
            if (in_array($orderBy[0], $this->model()->getFillable())) {
                $builder->orderBy($orderBy[0], $orderBy[1]);
            }
        }

        $groupBy = array_filter($groupBy, fn($key) => in_array($key, $this->model()->getFillable()));
        $groupBy = array_values($groupBy);

        if ($groupBy) {
            $builder->groupBy($groupBy);
        }

        if (strtolower($perPage) == 'all') {
            $get = true;
            $limit = null;
            $paginate = false;
        }

        if ($limit) {
            $builder->limit($limit);
        }

        if ($paginate) {
            return $builder->paginate(
                $perPage,
                pageName: $pageName
            );
        }

        if ($first) {
            return $builder->first();
        }

        if ($get) {
            return $builder->get();
        }

        return $builder;
    }
}
