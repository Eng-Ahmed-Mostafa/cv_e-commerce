<?php

namespace App\Interface\Api\Categories;

use App\Models\Category;

interface CategoryInterface
{
    public function getAllPaginated(int $perPage = 10);
    public function getAll();
    public function create(array $data);
    public function findCategory($id);
    public function update($category, array $data);
    public function delete(Category $category);
    public function updateImages(array $images, string $path): array;
    public function removeImage(array $images): void;
}
