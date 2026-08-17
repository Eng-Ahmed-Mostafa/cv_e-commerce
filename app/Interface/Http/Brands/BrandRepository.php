<?php

namespace App\Interface\Http\Brands;

use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class BrandRepository implements BrandInterface
{
    public function getAllPaginated(int $perPage = 10)
    {
        return Brand::paginate($perPage);
    }

    public function create(array $data): Brand
    {
        return Brand::create($data);
    }

    public function update(Brand $Brand, array $data): void
    {
        $Brand->update($data);
    }

    public function delete(Brand $Brand)
    {
        // Delete Images
        if (!empty($Brand->image)) {
            Storage::disk('public')->delete($Brand->image);
        }
        // Delete Brand
        $Brand->delete();
    }

    public function uploadImage(string $image, string $path): string
    {
        $imagePath = '';

        if ($image instanceof TemporaryUploadedFile) {
            $imagePath = $image->store($path, 'public');
        }

        return $imagePath;
    }


    public function removeImage(string $image): void
    {
        Storage::disk('public')->delete($image);
    }
}
