<?php

namespace App\Http\Controllers\Api\Admin;

use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Brands\BrandRequest;
use App\Http\Resources\BrandResource;
use App\Interface\Api\Brands\BrandInterface;
use Illuminate\Validation\ValidationException;

class BrandController extends Controller
{
    use ApiResponseTrait;

    protected BrandInterface $brandRepositoryAPI;

    public function __construct(BrandInterface $brandRepositoryAPI)
    {
        $this->brandRepositoryAPI = $brandRepositoryAPI;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = $this->brandRepositoryAPI->getAll();
        return $this->successResponse(BrandResource::collection($brands), 'Brands retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request)
    {
        $request->validated();

        $data = $request->only(['name', 'slug', 'image']);

        try {
            $brand = $this->brandRepositoryAPI->create($data);
            return $this->successResponse(new BrandResource($brand), 'Brand created successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create brand', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = $this->brandRepositoryAPI->findBrand($id);
        if (!$brand) {
            return $this->errorResponse('Brand not found', 404);
        }
        return $this->successResponse(new BrandResource($brand));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, string $id)
    {
        try {
            $request->validated();
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        }

        $slug = \Str::slug($request->slug);

        $brand = $this->brandRepositoryAPI->findBrand($id);

        if (!$brand) {
            return $this->errorResponse('Brand not found', 404);
        }

        $image = $brand->image;

        if ($request->hasFile('image')) {

            $image = $this->brandRepositoryAPI->updateImages($request->file('image'), 'brands');

            if (!empty($brand->image)) {
                $this->brandRepositoryAPI->removeImage($brand->image);
            }
        }

        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'image' => $image ?? $brand->image,
        ];
        $this->brandRepositoryAPI->update($brand, $data);

        return $this->successResponse(new BrandResource($brand), 'Brand updated success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = $this->brandRepositoryAPI->findBrand($id);
        if (!$brand) {
            return $this->errorResponse('Brand not found', 404);
        }

        if (!empty($brand->image)) {
            $this->brandRepositoryAPI->removeImage($brand->image);
        }
        $this->brandRepositoryAPI->delete($brand);
        return $this->successResponse(null, 'Brand deleted success');
    }
}
