<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\Product\ProductStoreRequest;
use App\Http\Requests\Products\Product\ProductUpdateRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use App\Repositories\ProductsRepository;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function __construct(protected ProductsRepository $repository)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->throwCannot("Products & Services.Catalogue", "List");

        return $this->query($this->repository, ProductResource::class, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        return $this->catch(fn(): mixed => $this->repository->create($request->only([
            'sku',
            'name',
            'category_id',
            'description',
            'price',
            'income_id',
            'photo_id',
            'purchase_description',
            'cost',
            'expense_id',
            'vendor_id',
            'file',
            'depository_id',
            'payable_id',
            'sales_tax_id',
            'wth_tax_id',
            'receivable_id',
        ])));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $this->catch(fn(): mixed => $this->repository->findByUuid($product->uuid), expectResponse: true);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        // throw new HttpResponseException(response()->json([
        //     'message' => $request->file
        // ], 500));
        $this->catch(fn(): mixed => $this->repository->update($request->only([
            'sku',
            'name',
            'category_id',
            'description',
            'price',
            'income_id',
            'photo_id',
            'purchase_description',
            'cost',
            'expense_id',
            'vendor_id',
            'file',
            'depository_id',
            'payable_id',
            'sales_tax_id',
            'wth_tax_id',
            'receivable_id'
        ]), $product->uuid));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, Request $request)
    {
        $request->user()->throwCannot("Products & Services.Catalogue", "Delete");

        $this->catch(fn(): mixed => $this->repository->delete($product->uuid));
    }
}
