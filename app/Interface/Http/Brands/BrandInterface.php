<?php

namespace App\Interface\Http\Brands;

use App\Models\Brand;

interface BrandInterface
{
    public function getAllPaginated(int $perPage = 10);

    public function create(array $data): Brand;

    public function update(Brand $Brand, array $data): void;

    public function delete(Brand $Brand);

    public function uploadImage($image, string $path);

    public function removeImage(string $image): void;
}
