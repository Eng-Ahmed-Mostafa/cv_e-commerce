<?php

namespace App\Interface\Http\Categories;

use App\Models\Category;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

interface CategoryInterface
{
    public function getAllPaginated(int $perPage = 10);

    public function create(array $data): Category;

    public function update(Category $category, array $data): void;

    public function delete(Category $category);

    public function uploadImages(array $images, string $path): array;

    public function removeImage(array $images): void;
}
