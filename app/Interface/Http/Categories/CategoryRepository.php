<?php

namespace App\Interface\Http\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CategoryRepository implements CategoryInterface
{
    public function getAllPaginated(int $perPage = 10)
    {
        return Category::paginate($perPage);
    }
    
    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data): void
    {
        $category->update($data);
    }

    public function delete(Category $category)
    {
        // Delete Images
        if (!empty($category->images)) {

            foreach ($category->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        // Delete Category
        $category->delete();
    }

    public function uploadImages(array $images, string $path): array
    {
        $storageImages = [];
        foreach ($images as $image) {
            if ($image instanceof TemporaryUploadedFile) {
                $storageImages[] = $image->store($path, 'public');
            }
        }

        return $storageImages;
    }

    public function removeImage(array $images): void
    {
        foreach ($images as $image) {
            Storage::disk('public')->delete($image);
        }
    }
}
