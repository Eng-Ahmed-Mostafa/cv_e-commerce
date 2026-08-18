<?php

namespace App\Interface\Http\Products;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ProductRepository implements ProductInterface
{
    public function getAllPaginated(int $perPage = 10)
    {
        return Product::paginate($perPage);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $Product, array $data): void
    {
        $Product->update($data);
    }

    public function delete(Product $Product)
    {
        // Delete Images
        if (!empty($Product->image)) {
            Storage::disk('public')->delete($Product->image);
        }
        // Delete Product
        $Product->delete();
    }

    public function uploadImage($image, string $path): string
    {
        $imagePath = '';

        if ($image instanceof TemporaryUploadedFile) {

            $imagePath = $image->store($path, 'public');
        }
        return $imagePath;
    }

    public function uploadImages(array $images, string $path): array
    {
        $imagePaths = [];

        foreach ($images as $image) {
            if ($image instanceof TemporaryUploadedFile) {
                $imagePath = $image->store($path, 'public');
                $imagePaths[] = $imagePath;
            }
        }
        return $imagePaths;
    }


    public function removeImage(string $image): void
    {
        Storage::disk('public')->delete($image);
    }
}
