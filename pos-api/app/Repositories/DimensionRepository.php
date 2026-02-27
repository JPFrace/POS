<?php
namespace App\Repositories;

use App\Models\Dimension;

class DimensionRepository extends Repository
{
    public function __construct(protected Dimension $model)
    {

    }

    public function create(array $data): Dimension
    {
        return \DB::transaction(function () use ($data) {
            return parent::create($data);
        });

    }

    public function update(array $data, $id, $key = 'uuid'): Dimension
    {
        return \DB::transaction(function () use ($data, $id, $key) {
            return parent::update($data, $id, $key);
        });
    }
}
