<?php

namespace App\Repositories;

use App\Models\ProductCategory;

class ProductCategoriesRepository extends Repository
{
    use Conditions\ProductCategoriesConditions;

    public function __construct(protected ProductCategory $model) {}
}
