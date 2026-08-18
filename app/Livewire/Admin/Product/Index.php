<?php

namespace App\Livewire\Admin\Product;

use App\Enum\ModeType;
use App\Interface\Http\Products\ProductInterface;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithPagination,WithFileUploads;

    /**
     * Properties
     */
    public ModeType $mode = ModeType::DISPLAY;
    public ?Product $product = null;
    protected ProductInterface $protectedRepository;

    public string $name = '';
    public string $slug = '';
    public string $price = '';
    public  $image = null;
    public array $images = [];
    public string $short_description = '';
    public string $description = '';
    public string $sale_price = '';
    public string $sku = '';
    public string $feature = '';
    public string $quantity = '';
    public string $stock = '';
    public string $category_id = '';
    public string $brand_id = '';
    public string $existingImage = '';
    public array $existingImages = [];

    public  $categories;
    public $brands;

    /**
     * Summary of boot
     * @param ProductInterface $protectedRepository
     * @return void
     */
    public function boot(ProductInterface $protectedRepository)
    {
        $this->protectedRepository = $protectedRepository;

    }

    /**
     * Summary of mount
     * @return void
     */
    public function mount() :void
    {
        $route = request()->route();

        $this->mode = match ($route->getName()) {
            'admin.product' => ModeType::DISPLAY,
            'admin.product.create' => ModeType::CREATE,
            'admin.product.edit' => ModeType::EDIT,
            default => ModeType::DISPLAY,
        };
        if ($this->mode === ModeType::EDIT) {
            $product = $route->parameter('product');

            if($product instanceof Product) {
                $this->product = $product;
                $this->name = $product->name;
                $this->slug = $product->slug;
                $this->price = $product->price;
                $this->short_description = $product->short_description;
                $this->description = $product->description;
                $this->sale_price = $product->sale_price;
                $this->sku = $product->SKU;
                $this->feature = $product->feature;
                $this->quantity = $product->quantity;
                $this->stock = $product->stock;
                $this->category_id = (string)$product->category_id;
                $this->brand_id = (string)$product->brand_id;
                $this->existingImage = $product->image ?? '';
                $this->existingImages = $product->images ?? [];
                $this->image = '';
                $this->images = [];
            }
        }
    }

    /**
     * Summary of render
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        $products = Product::paginate(10);
        $this->categories = Category::get();
        $this->brands = Brand::get();
        return view('livewire.admin.product.index', compact('products'))->layout('layouts.admin');
    }

    /**
     * Summary of rules
     * @return array{brand_id: string, category_id: string, description: string, feature: string, image: string, images.*: string, name: string, price: string, quantity: string, sale_price: string, short_description: string, sku: string, slug: string, stock: string}
     */
    protected function rules(): array
    {
        $slugRule = Rule::unique('products', 'slug');

        if ($this->mode === ModeType::EDIT && $this->product) {
            $slugRule->ignore($this->product->id);
        }

        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . ($this->product?->id ?? 'NULL') . ',id',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:100',
            'feature' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'stock' => 'required|string|in:instock,outstock',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ];
    }

    /**
     * Summary of storeProduct
     * @return void
     */
    public function storeProduct()
    {
        $this->mode = ModeType::CREATE;
        $this->validate();

        try {
            $productData = $this->productData();

            $this->protectedRepository->create($productData);
            $this->resetForm();
            session()->flash('success', 'Product created successfully.');

            $this->redirectRoute('admin.product', navigate: true);

        } catch (\Exception $e) {
            // Handle the exception, e.g., log it or display an error message
            session()->flash('error', 'An error occurred while saving the product: ' . $e->getMessage());
        }
    }

    /**
     * Summary of productData
     * @return array{SKU: string, brand_id: string, category_id: string, description: string, feature: string, image: string, images: string, name: string, price: string, quantity: string, sale_price: string, short_description: string, slug: string, stock: string}
     */
    public function productData(): array
    {
        $storedImage = $this->protectedRepository->uploadImage($this->image, 'products');

        $storedImages = $this->protectedRepository->uploadImages($this->images, 'products');

        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'image' => $storedImage,
            'images' => $storedImages,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'sale_price' => $this->sale_price,
            'SKU' => $this->sku,
            'feature' => $this->feature,
            'quantity' => $this->quantity,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
        ];
    }

    public function updateProduct(): void
    {
        if (!$this->product) {
            return;
        }

        $this->mode = ModeType::EDIT;

        $validatedData = $this->validate();

        try {
            $productData = $this->productData();

            $this->protectedRepository->update($this->product, $productData);

            if (!empty($productData['image'])) {
                $this->removeImage();
            }

            if (!empty($productData['images'])) {
                $this->removeImages();
            }

            $this->resetForm();

            session()->flash('success', 'Product updated successfully.');

            $this->redirectRoute('admin.product', navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating the product: ' . $e->getMessage());
        }
    }

    /**
     * Summary of removeImage
     * @return void
     */
    public function removeImage(): void
    {
        if ($this->mode === ModeType::EDIT) {
            $this->protectedRepository->removeImage($this->existingImage);
        }
    }

    /**
     * Summary of removeImages
     * @return void
     */
    public function removeImages(): void
    {
        if ($this->mode === ModeType::EDIT) {
            foreach ($this->existingImages as $existingImage) {
                $this->protectedRepository->removeImage($existingImage);
            }
        }
    }

    /**
     * Summary of resetForm
     * @return void
     */
    public function resetForm(): void
    {
        $this->reset([
            'name',
            'slug',
            'price',
            'image',
            'images',
            'short_description',
            'description',
            'sale_price',
            'sku',
            'feature',
            'quantity',
            'stock',
            'category_id',
            'brand_id',
            'existingImage',
            'existingImages'
        ]);

        $this->mode = ModeType::DISPLAY;
    }


    public function deleteProduct(int $id): void
    {
        try {
            $product = Product::findOrFail($id);
            $this->protectedRepository->delete($product);
            session()->flash('success', 'Product deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {

            if ($e->getCode() === '23000') {
                session()->flash('error', 'Cannot delete Product because it is associated with other records.');
            } else {
                session()->flash('error', 'Error deleting Product: ' . $e->getMessage());
            }
        } catch (\Throwable $e) {

            session()->flash('error', 'Error deleting Product: ' . $e->getMessage());
        }
    }
    /**
     * Summary of nameUpdated
     * @return void
     */
    public function updatedName()
    {
        $this->slug = \Str::slug($this->name);
    }
}
