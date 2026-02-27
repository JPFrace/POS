<?php

namespace App\Repositories;

use App\Models\Company;
use App\Supports\Utils\Upload;

class CompanyRepository extends Repository
{
    use Upload;

    public function __construct(protected Company $model)
    {

    }

    /**
     * Get the first company row.
     */
    public function getFirst()
    {
        return $this->model->first();
    }

    public function update(array $data, $id, $key = 'uuid'): Company
    {
        return \DB::transaction(function () use ($data, $id, $key) {
            $file = $data['file'] ?? null;
            unset($data['file']);

            $model = parent::update($data, $id, $key);

            if ($file) {
                if ($file = $this->upload($file, 'company')) {
                    $model->file()->associate($file);
                } else {
                    $model->file()->dissociate();
                }
            }

            $model->save();
            $model->refresh();

            return $model;
        });
    }
}
