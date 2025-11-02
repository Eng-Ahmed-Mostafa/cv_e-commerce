<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class BrandController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::get();
        return $this->successResponse(BrandResource::collection($brands), 'Brands retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'slug' => 'required|string|max:255|unique:brands,slug',
                'image' => 'nullable|mimes:png,jpg,jpeg|image|max:2048'
            ]);
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed',422,$e->errors());
        }
        
        $slug = \Str::slug($request->slug);

        $imagePath = $request->image ?? null;
        if($request->hasFile('image')) {
            $imagePath = $request->image->store('brands','public');
        }

        $brand = Brand::create([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $imagePath
        ]);

        return $this->successResponse(new BrandResource($brand), 'Brand created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = Brand::find($id);
        if(!$brand) {
            return $this->errorResponse('Brand not found', 404);
        }
        return $this->successResponse(new BrandResource($brand));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'slug' => 'required|string|max:255|unique:brands,slug,' . $id,
                'image' => 'nullable|mimes:png,jpg,jpeg|image|max:2048'
            ]);
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed',422,$e->errors());
        }

        $slug = \Str::slug($request->slug);


        $brand = Brand::find($id);

        if(!$brand) {
            return $this->errorResponse('Brand not found', 404);
        }

        $imagePath = $brand->image ?? null;
        if ($request->hasFile('image')) {
            $imageRemove = $brand->image;
            if (!empty($imageRemove)) {
                Storage::disk('public')->delete($imageRemove);
            }
            $imagePath = $request->image->store('brands', 'public');
        }

        $brand->update([
            'name' => $request->name,
            'slug' => $slug,
            'image' => $imagePath
        ]);

        return $this->successResponse(new BrandResource($brand),'Brand updated success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::find($id);
        if(!$brand) {
            return $this->errorResponse('Brand not found', 404);
        }

        if (!empty($brand->image)) {
            Storage::disk('public')->delete($brand->image);
        }
        $brand->delete();
        return $this->successResponse(null,'Brand deleted success');
    }
}
