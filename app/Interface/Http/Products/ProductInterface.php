<?php

namespace App\Interface\Http\Products;

use App\Models\Product;

interface ProductInterface
{
    public function getAllPaginated(int $perPage = 10);

    public function create(array $data): Product;

    public function update(Product $Product, array $data): void;

    public function delete(Product $Product);

    public function uploadImage($image, string $path): string;

    public function uploadImages(array $images, string $path): array;

    public function removeImage(string $image): void;
}
