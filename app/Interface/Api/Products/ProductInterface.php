<?php

namespace App\Interface\Api\Products;

use App\Models\Product;
use Illuminate\Http\UploadedFile;

interface ProductInterface
{
    public function getAllPaginated(int $perPage = 10);
    public function getAll();
    public function create(array $data);
    public function findProduct($id);
    public function update(Product $Product, array $data);
    public function delete(Product $Product);
    public function updateImages(UploadedFile $image, string $path): string;
    public function removeImage(string $image): void;
}
