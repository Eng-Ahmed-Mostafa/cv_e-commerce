<?php

namespace App\Livewire\Admin\Product;

use App\Models\Brand;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class Create extends Component
{
    use WithFileUploads;
    public $name;
    public $slug;
    public $image;
    public $short_description;
    public $description;
    public $images;
    public $price;
    public $sale_price;
    public $SKU;
    public $feature;
    public $stock;
    public $quantity;
    public $category_id;
    public $brand_id;

    public function save() {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'image' => 'required|image|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'images'=> 'nullable|array',
            'images.*' => 'nullable|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'SKU' => 'required|string|max:100',
            'feature' => 'required|boolean',
            'stock' => 'required',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ]);

        if($this->image instanceof TemporaryUploadedFile ) {

            $imageName = $this->image->store('products', 'public');
        }

        $galleryImages = [];

        if (!empty($this->images)) {
            foreach ($this->images as $img) {
                $galleryImages[] = $img->store('products', 'public');
            }
        }

        Product::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => $imageName,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'images' => implode(',', $galleryImages),
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'SKU' => $this->SKU,
            'feature' => $this->feature,
            'stock' => $this->stock,
            'quantity' => $this->quantity,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
        ]);

        
        // Reset form fields
        $this->reset();
        return $this->redirectRoute('admin.product', navigate: true);
    }

    public function updatingName($value) {
        $this->slug = \Str::slug($value);
    }
    public function render()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('livewire.admin.product.create', compact('categories', 'brands'))->layout('layouts.admin');
    }
}
