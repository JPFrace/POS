<?php

namespace App\Repositories;

use App\Models\File;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Http\UploadedFile;
use App\Supports\Utils\Upload;

class ProductsRepository extends Repository
{
    use Conditions\ProductConditions;
    use Upload;

    public function __construct(protected Product $model)
    {
    }

    public function create(array $data): Product
    {
        return \DB::transaction(function () use ($data) {
            $file = $data['file'] ?? null;
            unset($data['file']);

            $product = parent::create($data);

            if ($file) {


                if ($file = $this->upload($file, 'product')) {
                    $product->file()->associate($file);
                }
            }

            $product->save();

            return $product;
        });
    }

    public function update(array $data, $id, $key = 'uuid'): Product
    {
        return \DB::transaction(function () use ($data, $id, $key) {
            $file = $data['file'] ?? null;
            unset($data['file']);

            $product = parent::update(
                $data,
                $id,
                $key
            );

            if ($file) {
                if ($file = $this->upload($file, 'product')) {
                    $product->file()->associate($file);
                }
            } else {
                $product->file()->dissociate();
            }

            $product->save();

            return $product;
        });
    }

    public function delete(string|int|array $id, $key = 'uuid'): bool|null
    {
        $product = $this->model()->where('uuid', $id)->first();
        if ($product->photo_id) {
            $file = File::findOrFail($product->photo_id);
            File::findOrFail($product->photo_id)->delete();
        }
        return $this->model()->findOrFail($product->id)->delete();
    }
}
