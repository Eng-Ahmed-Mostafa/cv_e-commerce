<?php

namespace App\Livewire\Admin\Category;

use App\Enum\ModeType;
use App\Interface\Http\Categories\CategoryInterface;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithFileUploads, WithPagination;


    /**
     * Properties
     */
    public ModeType $mode = ModeType::DISPLAY;

    public ?Category $category = null;

    protected CategoryInterface $categoryRepository;

    public string $name = '';

    public string $slug = '';

    // New uploaded images [array,TemporaryUploadedFile]
    public array $images = [];

    // Existing images from database [array,string]
    public array $existingImages = [];

    /**
     * Summary of boot
     * @param CategoryInterface $categoryRepository
     * @return void
     */
    public function boot(CategoryInterface $categoryRepository): void
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Summary of mount
     * @return void
     */
    public function mount(): void
    {
        $route = request()->route();

        $this->mode = match ($route->getName()) {
            'admin.category' => ModeType::DISPLAY,
            'admin.category.create' => ModeType::CREATE,
            'admin.category.edit' => ModeType::EDIT,
            default => ModeType::DISPLAY,
        };

        if ($this->mode === ModeType::EDIT) {

            $category = $route->parameter('category');

            if ($category instanceof Category) {
                $this->category = $category;
                $this->name = $category->name;
                $this->slug = $category->slug;
                $this->existingImages = $category->images ?? [];
                $this->images = [];
            }
        }
    }

    /**
     * Summary of render
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        $categories = $this->mode === ModeType::DISPLAY ? $this->categoryRepository->getAllPaginated() : collect();
        return view('livewire.admin.category.index', compact('categories'))->layout('layouts.admin');
    }



    /**
     * Summary of rules
     * @return array{images: string[], images.*: string[], name: string[], slug: array<string|\Illuminate\Validation\Rules\Unique>}
     */
    protected function rules(): array
    {
        $slugRule = Rule::unique('categories', 'slug');

        if ($this->mode === ModeType::EDIT && $this->category) {
            $slugRule->ignore($this->category->id);
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', $slugRule],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];
    }



    /**
     * Summary of storeCategory
     * @return void
     */
    public function storeCategory(): void {

        $this->validate();

        try {
            $data = $this->categoryData();

            $this->categoryRepository->create($data);

            $this->resetForm();

            session()->flash('success', 'Category created successfully.');

            $this->redirectRoute('admin.category', navigate: true);
        } catch (\Throwable $e) {
            session()->flash('error', 'Error creating category: ' . $e->getMessage());
        }
    }



    /**
     * Summary of updateCategory
     * @return void
     */
    public function updateCategory(): void
    {
        if (!$this->category) {
            return;
        }

        $this->mode = ModeType::EDIT;

        $this->validate();

        try {
            $data = $this->categoryData();

            $this->categoryRepository->update($this->category, $data);

            if (!empty($data['images'])) {
                $this->removeImage();
            }

            $this->resetForm();

            session()->flash('success', 'Category updated successfully.');

            $this->redirectRoute('admin.category', navigate: true);
        } catch (\Throwable $e) {
            session()->flash('error', 'Error updating category: ' . $e->getMessage());
        }
    }

    /**
     * Summary of uploadImages
     * @param mixed $path
     * @return array<bool|string>
     */
    public function uploadImages($path): array
    {
        return $this->categoryRepository->uploadImages($this->images, $path);
    }

    /**
     * Summary of removeImage
     * @return void
     */
    public function removeImage(): void
    {
        if ($this->mode === ModeType::EDIT) {
            $this->categoryRepository->removeImage($this->existingImages);
        }
    }

    /**
     * Summary of categoryData
     * @return array{images: array<bool|string>, name: string, slug: string}
     */
    protected function categoryData(): array
    {
        $storageImages = $this->uploadImages('categories');

        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'images' => $storageImages,
        ];
    }


    /**
     * Summary of deleteCategory
     * @param int $id
     * @return void
     */
    public function deleteCategory(int $id): void
    {
        try {

            $category = Category::findOrFail($id);

            $this->categoryRepository->delete($category);

            session()->flash('success', 'Category deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {

            if ($e->getCode() === '23000') {
                session()->flash('error', 'Cannot delete category because it is associated with other records.');
            } else {
                session()->flash('error', 'Error deleting category: ' . $e->getMessage());
            }
        } catch (\Throwable $e) {

            session()->flash('error', 'Error deleting category: ' . $e->getMessage());
        }
    }


    /**
     * Summary of resetForm
     * @return void
     */
    protected function resetForm(): void
    {
        $this->reset([
            'category',
            'name',
            'slug',
            'images',
            'existingImages',
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
