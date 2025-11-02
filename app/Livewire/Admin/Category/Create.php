<?php

namespace App\Livewire\Admin\Category;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithFileUploads;
use \Str;

class Create extends Component
{
    use WithFileUploads;
    public $name = "";
    public $slug = "";
    public $images;

    public function updatingName($value) {
        $this->slug = Str::slug($value);
    }
    public function save() {
        $this->validate([
            'name'=> "required",
            "slug"=>'required|unique:categories,slug|string|max:255',
            'images' => 'nullable|array',
            "images.*"=> "nullable|image|mimes:jpeg,png,jpg,gif|max:2048"
        ]);

    
        foreach ($this->images as $key => $image) {
            $this->images[$key] = $image->store('categories','public');
        }


        Category::create([
            'name'=> $this->name,
            "slug"=> $this->slug,
            "images"=> json_encode($this->images)
        ]);
        
        $this->reset(['name','slug','images']);
        return $this->redirectRoute('admin.category',navigate:true);
    }

    public function render()
    {
        return view('livewire.admin.category.create')->layout('layouts.admin');
    }
}
