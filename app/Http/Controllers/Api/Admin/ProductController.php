<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Interface\Api\Products\ProductInterface;
use App\Models\Product;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    use ApiResponseTrait;

    public ProductInterface $productRepository;

    public function __construct(ProductInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = $this->productRepository->getAllPaginated(10);

        return $this->successResponse(ProductResource::collection($products), 'Products retrieved successfully');
    }

    public function productData() {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $request->validated();

        try {
            $product = $this->productRepository->create($request->all());

            return $this->successResponse(new ProductResource($product), 'Product created successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create product', 500, ['error' => $e->getMessage()]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->productRepository->findProduct($id);
        if (! $product) {
            return $this->errorResponse('Product not found', 404);
        }

        return $this->successResponse(new ProductResource($product));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {

        $request->validated();


        $product = Product::find($id);

        if (! $product) {
            return  $this->errorResponse('Product not found', 404);
        }

        return $this->successResponse(new ProductResource($product), 'Product updated success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (! $product) {
            return $this->errorResponse('Product not found', 404);
        }

        try {
            $this->productRepository->delete($product);

            return $this->successResponse(null, 'Product deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete product', 500, ['error' => $e->getMessage()]);
        }
    }
}
