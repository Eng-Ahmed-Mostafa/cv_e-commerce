<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProductResource;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category','brand'])->get();
        return $this->successResponse(ProductResource::collection($products), 'Products retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'slug' => 'required|string|max:255|unique:products,slug',
                'image' => 'nullable|mimes:png,jpg,jpeg|image|max:2048',
                'images.*' => 'nullable|mimes:png,jpg,jpeg|image|max:2048',
                'short_description' => "required|string|max:255",
                'description' => "required|string",
                'price' => "required",
                'sale_price' => "required",
                "SKU" => "required",
                "feature" => "required",
                "stock" => "required",
                "quantity" => "required",
                "category_id" => "required",
                "brand_id" => "required"
            ]);
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed',422,$e->errors());
        }
        
        $slug = \Str::slug($request->slug);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->image->store('products', 'public');
        }

        $imagesPath = null;
        if($request->hasFile('images')) {
            $imagesPath = [];
            foreach($request->file('images') as $image)
            {
                $imagesPath[] = $image->store('products','public');
            }
        }

        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $imagePath,
            'images' => json_encode($imagesPath),
            'short_description' => $request->short_description,
            'description' => $request->description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            "SKU" => $request->SKU,
            "feature" => $request->feature,
            "stock" => $request->stock,
            "quantity" => $request->quantity,
            "category_id" => $request->category_id,
            "brand_id" => $request->brand_id
        ]);

        return $this->successResponse(new ProductResource($product), 'Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with(['category','brand'])->find($id);
        if(!$product) {
            return $this->errorResponse('Product not found', 404);
        }
        return $this->successResponse(new ProductResource($product));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'slug' => 'required|string|max:255|unique:products,slug,' . $id,
                'image' => 'nullable|mimes:png,jpg,jpeg|image|max:2048',
                'images.*' => 'nullable|mimes:png,jpg,jpeg|image|max:2048',
                'short_description' => "required|string|max:255",
                'description' => "required|string",
                'price' => "required",
                'sale_price' => "required",
                "SKU" => "required",
                "feature" => "required",
                "stock" => "required",
                "quantity" => "required",
                "category_id" => "required",
                "brand_id" => "required"

            ]);
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed',422,$e->errors());
        }

        $slug = \Str::slug($request->slug);


        $product = Product::find($id);

        if(!$product) {
            return $this->errorResponse('Product not found', 404);
        }
        $imagePath = $product->image ?? null; 
        if ($request->hasFile('image')) {
            if (!empty($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->image->store('products', 'public');
        }

        $imagesPath = json_decode($product->images,true) ?? [];
        if ($request->hasFile('images')) {
            $imageRemove = $imagesPath;
            foreach($imageRemove as $image) {
                if (!empty($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
            $imagesPath = [];
            foreach($request->file('images') as $image) {

                $imagesPath[] = $image->store('products', 'public');
            }
        }

        $product->update([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $imagePath,
            'images' => json_encode($imagesPath),
            'short_description' => $request->short_description,
            'description' => $request->description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            "SKU" => $request->SKU,
            "feature" => $request->feature,
            "stock" => $request->stock,
            "quantity" => $request->quantity,
            "category_id" => $request->category_id,
            "brand_id" => $request->brand_id
        ]);

        return $this->successResponse(new ProductResource($product),'Product updated success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if(!$product) {
            return $this->errorResponse('Product not found', 404);
        }

        if (!empty($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        if (!empty($product->images)) {
            foreach (json_decode($product->images, true) as $imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
        }
        $product->delete();
        return $this->successResponse(null,'Product deleted success');
    }
}
