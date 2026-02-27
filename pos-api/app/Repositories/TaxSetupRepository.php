<?php

namespace App\Repositories;

use App\Models\TaxSetup;
use App\Supports\Utils\Upload;

class TaxSetupRepository extends Repository
{
    use Conditions\TaxSetupConditions;


    public function __construct(protected TaxSetup $model)
    {
    }

    public function update(array $data, $id, $key = 'uuid'): TaxSetup
    {
        return \DB::transaction(function () use ($data, $id, $key) {

            $tax_setup = parent::update(
                $data,
                $id,
                $key
            );

            $tax_setup->save();

            return $tax_setup;
        });
    }
}
