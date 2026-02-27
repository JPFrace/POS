<?php

namespace App\Repositories;

use App\Models\Tax;
use App\Models\File;

class TaxRepository extends Repository
{
    use Conditions\TaxConditions;

    public function __construct(protected Tax $model)
    {

    }
    public function create(array $data): Tax
    {
        return \DB::transaction(function () use ($data) {

            $tax = parent::create($data);

            $tax->save();

            return $tax;
        });
    }

    public function update(array $data, $id, $key = 'uuid'): Tax
    {
        return \DB::transaction(function () use ($data, $id, $key) {

            $tax = parent::update(
                $data,
                $id,
                $key
            );

            $tax->save();

            return $tax;
        });
    }

    public function delete(string|int|array $id, $key = 'uuid'): bool|null
    {
        $tax = $this->model()->where('uuid', $id)->first();

        return $this->model()->findOrFail($tax->id)->delete();
    }
    protected function whereUuid($builder, string $relation, string $uuid)
    {
        return $builder->whereHas($relation, fn($q) => $q->where('uuid', $uuid));
    }
}