<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductCategory\ProductCategoryStoreRequest;
use App\Http\Requests\Products\ProductCategory\ProductCategoryUpdateRequest;
use App\Http\Resources\Product\ProductCategoryResource;
use App\Models\ProductCategory;
use App\Repositories\ProductCategoriesRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class ProductCategoriesController extends Controller
{

    public function __construct(protected ProductCategoriesRepository $repository)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Products & Services.Categories", "List");

        return $this->query($this->repository, ProductCategoryResource::class, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCategoryStoreRequest $request)
    {
        $this->catch(fn(): mixed => $this->repository->create($request->only([
            'name',
            'description',
            'parent_id'
        ])), expectResponse: false);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductCategoryUpdateRequest $request, ProductCategory $product_category)
    {
        $this->catch(fn(): mixed => $this->repository->update($request->only([
            'name',
            'description',
            'parent_id'
        ]), $product_category->uuid, 'uuid'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $product_category, Request $request)
    {
        $request->user()->throwCannot("Products & Services.Categories", "Delete");

        $this->catch(fn(): mixed => $this->repository->delete($product_category->uuid));
    }
}
