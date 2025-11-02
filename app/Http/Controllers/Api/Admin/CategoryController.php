<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoryResource;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::get();
        return $this->successResponse(CategoryResource::collection($categories), 'Categorys retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'slug' => 'required|string|max:255|unique:categories,slug',
                'images.*' => 'nullable|mimes:png,jpg,jpeg|image|max:2048'
            ]);
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed',422,$e->errors());
        }
        
        $slug = \Str::slug($request->slug);

        $imagesPath = $request->images ?? null;

        if($request->hasFile('images')) {
            $imagesPath = [];
            foreach($request->file('images') as $image)
            {
                $imagesPath[] = $image->store('categories','public');
            }
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'images' => json_encode($imagesPath)
        ]);

        return $this->successResponse(new CategoryResource($category), 'Category created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);
        if(!$category) {
            return $this->errorResponse('Category not found', 404);
        }
        return $this->successResponse(new CategoryResource($category));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name' => 'required',
                'slug' => 'required|string|max:255|unique:categories,slug,' . $id,
                'images.*' => 'nullable|mimes:png,jpg,jpeg|image|max:2048'
            ]);
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed',422,$e->errors());
        }

        $slug = \Str::slug($request->slug);


        $category = Category::find($id);

        if(!$category) {
            return $this->errorResponse('Category not found', 404);
        }

        $imagesPath = json_decode($category->images,true) ?? [];
        if ($request->hasFile('images')) {
            $imageRemove = $imagesPath;
            foreach($imageRemove as $image) {
                if (!empty($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
            $imagesPath = [];
            foreach($request->file('images') as $image) {

                $imagesPath[] = $image->store('categories', 'public');
            }
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'images' => json_encode($imagesPath)
        ]);

        return $this->successResponse(new CategoryResource($category),'Category updated success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if(!$category) {
            return $this->errorResponse('Category not found', 404);
        }

        if (!empty($category->images)) {
            foreach (json_decode($category->images, true) as $imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
        }
        $category->delete();
        return $this->successResponse(null,'Category deleted success');
    }
}
