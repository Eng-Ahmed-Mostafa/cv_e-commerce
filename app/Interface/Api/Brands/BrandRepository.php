<?php

namespace App\Interface\Api\Brands;

use App\Models\Brand;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BrandRepository implements BrandInterface
{
    public function getAllPaginated(int $perPage = 10)
    {
        return Brand::paginate($perPage);
    }

    public function getAll()
    {
        return Brand::all();
    }

    public function create(array $data)
    {
        $slug = \Str::slug($data['slug']);

        $imagePath = $data['image'] ?? null;

        if (!empty($imagePath)) {
            $imagePath = $imagePath->store('brands', 'public');
        }

        $brand = Brand::create([
            'name' => $data['name'],
            'slug' => $slug,
            'image' => $imagePath
        ]);
        return $brand;
    }

    public function findBrand($id)
    {
        return Brand::find($id);
    }

    public function update(Brand $brand, array $data)
    {

        $brand->update([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'image' =>$data['image']
        ]);
    }

    public function delete(Brand $brand)
    {
        $brand->delete();
    }

    public function updateImages(UploadedFile $image, string $path): string
    {
        $imagePath = '';


        $imagePath = $image->store($path, 'public');

        return $imagePath;

    }

    public function removeImage(string $image): void
    {
        Storage::disk('public')->delete($image);
    }
}
