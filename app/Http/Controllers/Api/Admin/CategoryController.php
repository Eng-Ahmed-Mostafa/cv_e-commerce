<?php

namespace App\Http\Controllers\Api\Admin;

use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Interface\Api\Categories\CategoryInterface;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    protected CategoryInterface $categoryRepositoryAPI;

    public function __construct(CategoryInterface $categoryRepositoryAPI)
    {
        $this->categoryRepositoryAPI = $categoryRepositoryAPI;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->categoryRepositoryAPI->getAll();
        return $this->successResponse(CategoryResource::collection($categories), 'Categories retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $request->validated();

        $data = $request->only(['name', 'slug', 'images']);

        try {
            $category = $this->categoryRepositoryAPI->create($data);
            return $this->successResponse(new CategoryResource($category), 'Category created successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create category', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = $this->categoryRepositoryAPI->findCategory($id);
        if (!$category) {
            return $this->errorResponse('Category not found', 404);
        }
        return $this->successResponse(new CategoryResource($category));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        try {
            $request->validated();
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        }

        $slug = \Str::slug($request->slug);


        $category = $this->categoryRepositoryAPI->findCategory($id);

        if (!$category) {
            return $this->errorResponse('Category not found', 404);
        }


        $images = $this->categoryRepositoryAPI->updateImages($request->file('images', []), 'categories');

        if (!empty($request->file('images'))) {
            // Delete old images
            $this->categoryRepositoryAPI->removeImage($category->images);
        }

        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'images' => $images
        ];
        $this->categoryRepositoryAPI->update($category, $data);

        return $this->successResponse(new CategoryResource($category), 'Category updated success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = $this->categoryRepositoryAPI->findCategory($id);
        if (!$category) {
            return $this->errorResponse('Category not found', 404);
        }

        if (!empty($category->images)) {
            $this->categoryRepositoryAPI->removeImage($category->images);
        }
        $this->categoryRepositoryAPI->delete($category);
        return $this->successResponse(null, 'Category deleted success');
    }
}
