<?php

namespace App\Interface\Api\Products;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductRepository implements ProductInterface
{
    public function getAllPaginated(int $perPage = 10)
    {
        return Product::with(['category', 'brand'])->paginate($perPage);
    }

    public function getAll()
    {
        return Product::with(['category', 'brand'])->get();
    }

    public function create(array $data)
    {
        $slug = \Str::slug($data['slug']);

        $imagePath = null;
        if (isset($data['image']) && $data['image']->isValid()) {
            $imagePath = $this->updateImages($data['image'], 'products');
        }

        $imagesPath = null;
        if (isset($data['images']) && is_array($data['images'])) {
            $imagesPath = [];
            foreach ($data['images'] as $image) {
                $imagesPath[] = $this->updateImages($image, 'products');
            }
        }

        $product = Product::create([
            'name' => $data['name'],
            'slug' => $slug,
            'image' => $imagePath,
            'images' => $imagesPath,
            'short_description' => $data['short_description'],
            'description' => $data['description'],
            'price' => $data['price'],
            'sale_price' => $data['sale_price'],
            'SKU' => $data['SKU'],
            'feature' => $data['feature'],
            'stock' => $data['stock'],
            'quantity' => $data['quantity'],
            'category_id' => $data['category_id'],
            'brand_id' => $data['brand_id'],
        ]);

        return $product;
    }

    public function findProduct($id)
    {
        return Product::with(['category', 'brand'])->find($id);
    }

    public function update(Product $Product, array $data)
    {

        $slug = \Str::slug($data['slug']);

        $imagePath = $Product->image ?? null;
        if ($data['image']) {
            if (! empty($Product->image)) {
                Storage::disk('public')->delete($Product->image);
            }
            $imagePath = $this->updateImages($data['image'], 'products');
        }

        $imagesPath = $Product->images ?? [];
        if ($data['images']) {
            $imageRemove = $imagesPath;
            foreach ($imageRemove as $image) {
                if (! empty($image)) {
                    $this->removeImage($image);
                }
            }
            $imagesPath = [];
            foreach ($data['images'] as $image) {
                $imagesPath[] = $this->updateImages($image, 'products');
            }
        }

        $Product->update([
            'name' => $data['name'],
            'slug' => $slug,
            'image' => $imagePath,
            'images' => $imagesPath,
            'short_description' => $data['short_description'],
            'description' => $data['description'],
            'price' => $data['price'],
            'sale_price' => $data['sale_price'],
            'SKU' => $data['SKU'],
            'feature' => $data['feature'],
            'stock' => $data['stock'],
            'quantity' => $data['quantity'],
            'category_id' => $data['category_id'],
            'brand_id' => $data['brand_id'],
        ]);
    }

    public function delete(Product $Product)
    {
        if (! empty($Product->image)) {
            $this->removeImage($Product->image);
        }

        if (! empty($Product->images)) {
            foreach ($Product->images as $image) {
                $this->removeImage($image);
            }
        }
        $Product->delete();
    }

    public function updateImages(UploadedFile $image, string $path): string
    {
        $imagePath = '';

        $imagePath = $image->store($path, 'public');

        return $imagePath;

    }

    public function removeImage(string $image): void
    {
        Storage::disk('public')->delete($image);
    }
}
