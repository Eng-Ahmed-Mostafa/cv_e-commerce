<?php

namespace App\Livewire\Admin\Brand;

use App\Enum\ModeType;
use App\Interface\Http\Brands\BrandInterface;
use App\Models\Brand;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;


class Index extends Component
{
    use WithFileUploads, WithPagination;


    /**
     * Properties
     */
    public ModeType $mode = ModeType::DISPLAY;

    public ?Brand $brand = null;

    protected BrandInterface $brandRepository;

    public string $name = '';

    public string $slug = '';

    public string $image = '';

    public string $existingImage = '';

    /**
     * Summary of boot
     * @param BrandInterface $brandRepository
     * @return void
     */
    public function boot(BrandInterface $brandRepository): void
    {
        $this->brandRepository = $brandRepository;
    }

    /**
     * Summary of mount
     * @return void
     */
    public function mount(): void
    {
        $route = request()->route();

        $this->mode = match ($route->getName()) {
            'admin.brand' => ModeType::DISPLAY,
            'admin.brand.create' => ModeType::CREATE,
            'admin.brand.edit' => ModeType::EDIT,
            default => ModeType::DISPLAY,
        };

        if ($this->mode === ModeType::EDIT) {

            $brand = $route->parameter('brand');

            if ($brand instanceof Brand) {
                $this->brand = $brand;
                $this->name = $brand->name;
                $this->slug = $brand->slug;
                $this->existingImage = $brand->image ?? '';
                $this->image = '';
            }
        }
    }

    /**
     * Summary of render
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        $brands = $this->mode === ModeType::DISPLAY ? $this->brandRepository->getAllPaginated() : collect();
        return view('livewire.admin.brand.index', compact('brands'))->layout('layouts.admin');
    }



    /**
     * Summary of rules
     * @return array{image: string[], image.*: string[], name: string[], slug: array<string|\Illuminate\Validation\Rules\Unique>}
     */
    protected function rules(): array
    {
        $slugRule = Rule::unique('brands', 'slug');

        if ($this->mode === ModeType::EDIT && $this->brand) {
            $slugRule->ignore($this->brand->id);
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', $slugRule],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:1024'],
        ];
    }



    /**
     * Summary of storeBrand
     * @return void
     */
    public function storeBrand(): void {

        $this->validate();

        try {
            $data = $this->brandData();

            $this->brandRepository->create($data);

            $this->resetForm();

            session()->flash('success', 'Brand created successfully.');

            $this->redirectRoute('admin.brand', navigate: true);
        } catch (\Throwable $e) {
            session()->flash('error', 'Error creating brand: ' . $e->getMessage());
        }
    }



    /**
     * Summary of updateBrand
     * @return void
     */
    public function updateBrand(): void
    {
        if (!$this->brand) {
            return;
        }

        $this->mode = ModeType::EDIT;

        $this->validate();

        try {
            $data = $this->brandData();

            $this->brandRepository->update($this->brand, $data);

            if (!empty($data['image'])) {
                $this->removeImage();
            }

            $this->resetForm();

            session()->flash('success', 'Brand updated successfully.');

            $this->redirectRoute('admin.brand', navigate: true);
        } catch (\Throwable $e) {
            session()->flash('error', 'Error updating brand: ' . $e->getMessage());
        }
    }

    /**
     * Summary of uploadImage
     * @param mixed $path
     * @return array<bool|string>
     */
    public function uploadImages($path): array
    {
        return $this->brandRepository->uploadImage($this->image, $path);
    }

    /**
     * Summary of removeImage
     * @return void
     */
    public function removeImage(): void
    {
        if ($this->mode === ModeType::EDIT) {
            $this->brandRepository->removeImage($this->existingImage);
        }
    }

    /**
     * Summary of brandData
     * @return array{image: array<bool|string>, name: string, slug: string}
     */
    protected function brandData(): array
    {
        $storageImage = $this->uploadImage('brands');

        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => $storageImage,
        ];
    }


    /**
     * Summary of deleteBrand
     * @param int $id
     * @return void
     */
    public function deleteBrand(int $id): void
    {
        try {

            $brand = Brand::findOrFail($id);

            $this->brandRepository->delete($brand);

            session()->flash('success', 'Brand deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {

            if ($e->getCode() === '23000') {
                session()->flash('error', 'Cannot delete brand because it is associated with other records.');
            } else {
                session()->flash('error', 'Error deleting brand: ' . $e->getMessage());
            }
        } catch (\Throwable $e) {

            session()->flash('error', 'Error deleting brand: ' . $e->getMessage());
        }
    }


    /**
     * Summary of resetForm
     * @return void
     */
    protected function resetForm(): void
    {
        $this->reset([
            'brand',
            'name',
            'slug',
            'image',
            'existingImage',
        ]);

        $this->mode = ModeType::DISPLAY;
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
