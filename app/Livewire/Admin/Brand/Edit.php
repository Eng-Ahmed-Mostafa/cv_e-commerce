<?php

namespace App\Livewire\Admin\Brand;

use App\Models\Brand;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $brand;
    public $name;
    public $slug;
    public $image;

    public function mount($brand) {
        $this->brand = Brand::find($brand);
        $this->name = $this->brand->name;
        $this->slug = $this->brand->slug;
        $this->image = $this->brand->image;
    }
    public function updatingName($value) {
        $this->slug = \Str::slug($value);
    }
    public function save() {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug,' . $this->brand->id,
        ];

        if ($this->image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }else {
            $rules['image'] = 'nullable|string|max:255';
        }

        $this->validate($rules);

        if ($this->image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            $this->image = $this->image->store('brands', 'public');
        } else {
            $this->image = $this->brand->image;
        }

        

        $this->brand->update([
            'name'=> $this->name,
            "slug"=> $this->slug,
            "image"=> $this->image
        ]);
        
        $this->reset(['name','slug','image']);
        return $this->redirectRoute('admin.brand',navigate:true);
    }
    public function render()
    {
        return view('livewire.admin.brand.edit')->layout('layouts.admin');
    }
}
