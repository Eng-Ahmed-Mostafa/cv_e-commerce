<?php

namespace App\Interface\Api\Brands;

use App\Models\Brand;
use Illuminate\Http\UploadedFile;

interface BrandInterface
{
    public function getAllPaginated(int $perPage = 10);
    public function getAll();
    public function create(array $data);
    public function findBrand($id);
    public function update(Brand $brand, array $data);
    public function delete(Brand $brand);
    public function updateImages(UploadedFile $image, string $path): string;
    public function removeImage(string $image): void;
}
