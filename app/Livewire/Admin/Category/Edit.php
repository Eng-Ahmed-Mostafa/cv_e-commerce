<?php

namespace App\Livewire\Admin\Category;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;
    public $category;
    public $name;
    public $slug;
    public $images;

    public function mount($category) {
        $this->category = Category::find($category);
        $this->name = $this->category->name;
        $this->slug = $this->category->slug;
        $this->images = json_decode($this->category->images, true) ?? [];
        
    }
    public function updatingName($value) {
        $this->slug = \Str::slug($value);
    }
    public function save() {

        $rules = [
            'name'=> "required",
            "slug" => 'required|string|max:255|unique:categories,slug,' . $this->category->id,
        ];

        if ($this->images instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            $rules['images'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        $this->validate($rules);

        $storageImages = [];
    
        if (!is_array($this->images)) {
            $this->images = json_decode($this->images, true);
        }
        foreach ($this->images as $image) {
            if ($image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                $storageImages[] = $image->store('categories', 'public');
            }
            else {
                $storageImages = $image;
            }
        }

        // $this->images = json_encode($this->images);

        $this->category->update([
            'name'=> $this->name,
            "slug"=> $this->slug,
            "images"=> json_encode($storageImages)
        ]);
        
        $this->reset(['name','slug','images']);
        return $this->redirectRoute('admin.category',navigate:true);
    }
    public function render()
    {
        return view('livewire.admin.category.edit')->layout('layouts.admin');
    }
}
