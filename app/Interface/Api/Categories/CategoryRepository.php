<?php

namespace App\Interface\Api\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryRepository implements CategoryInterface
{
    public function getAllPaginated(int $perPage = 10)
    {
        return Category::paginate($perPage);
    }

    public function getAll()
    {
        return Category::all();
    }

    public function create(array $data)
    {
        $slug = \Str::slug($data['slug']);

        $imagesPath = $data['images'] ?? null;

        if (!empty($imagesPath) && is_array($imagesPath)) {
            $imagesPath = [];
            foreach ($data['images'] as $image) {
                $imagesPath[] = $image->store('categories', 'public');
            }
        }

        $category = Category::create([
            'name' => $data['name'],
            'slug' => $slug,
            'images' => $imagesPath
        ]);
        return $category;
    }

    public function findCategory($id)
    {
        return Category::find($id);
    }

    public function update($category, array $data)
    {

        $category->update([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'images' =>$data['images']
        ]);
    }

    public function delete(Category $category)
    {
        $category->delete();
    }

    public function updateImages(array $images, string $path): array
    {
        $imagePaths = [];

        foreach ($images as $image) {
            $imagePaths[] = $image->store($path, 'public');
        }

        return $imagePaths;

    }

    public function removeImage(array $images): void
    {
        foreach ($images as $imagePath) {
            Storage::disk('public')->delete($imagePath);
        }
    }
}
