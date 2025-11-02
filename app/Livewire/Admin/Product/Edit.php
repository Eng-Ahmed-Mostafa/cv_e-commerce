<?php

namespace App\Livewire\Admin\Product;

use \Str;
use App\Models\Brand;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithFileUploads;
use PhpParser\Node\Stmt\Catch_;
use Illuminate\Support\Facades\Storage;

class Edit extends Component
{
    use WithFileUploads;
    public $product;
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
    public $categories;
    public $brands;

    public function mount($product) {
        $this->product = Product::find($product);
        $this->name = $this->product->name;
        $this->slug = $this->product->slug;
        $this->image = $this->product->image;
        $this->short_description = $this->product->short_description;
        $this->description = $this->product->description;
        $this->price = $this->product->price;
        $this->sale_price = $this->product->sale_price;
        $this->SKU = $this->product->SKU;
        $this->feature = $this->product->feature;
        $this->stock = $this->product->stock;
        $this->quantity = $this->product->quantity;
        $this->category_id = $this->product->category_id;
        $this->brand_id = $this->product->brand_id;
        $this->categories = Category::all();
        $this->brands = Brand::all();
        $this->images = json_decode($this->product->images, true) ?? [];
    }
    public function save() {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $this->product->id,
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'SKU' => 'required|string|max:100',
            'feature' => 'required|boolean',
            'stock' => 'required',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ];

        if( $this->image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }else {
            $rules['image'] = 'nullable|string|max:255';
        }

        if (!empty($this->images)) {
            foreach ($this->images as $key => $img) {
                if ($img instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                    $rules['images.' . $key] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
                } else {
                    $rules['images.' . $key] = 'nullable|string|max:255';
                }
            }
        }
        $this->validate($rules);


        $imagePath = $this->product->image;

        if( $this->image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            $imageRemove = $this->product->image;
            if (!empty($imageRemove)) {
                Storage::disk('public')->delete($imageRemove);
            }
            $imagePath = $this->image->store('products', 'public');
        }

        $galleryImages = [];

        if(!is_array($this->images)) {
            $this->images = json_decode($this->images, true);
        }

        if (!empty($this->images)) {
            foreach ($this->images as $img) {
                if( $img instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                    $galleryImages[] = $img->store('products', 'public');
                }else {
                    $galleryImages[] = $img;
                }
            }
        }

        $product = $this->product;
        $imagesRemove = $product->images;

        $product->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => $imagePath,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'images' => json_encode($galleryImages),
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'SKU' => $this->SKU,
            'feature' => $this->feature,
            'stock' => $this->stock,
            'quantity' => $this->quantity,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
        ]);
        if (!empty($imagesRemove)) {

            foreach (json_decode($imagesRemove, true) ?? [] as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        
        // Reset form fields
        $this->reset();
        return $this->redirectRoute('admin.product', navigate: true);
    }

    public function updatingName($value) {
        $this->slug = \Str::slug($value);
    }

    public function render()
    {
        return view('livewire.admin.product.edit')->layout('layouts.admin');
    }
}
