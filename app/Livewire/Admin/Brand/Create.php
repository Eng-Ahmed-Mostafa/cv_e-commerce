<?php

namespace App\Livewire\Admin\Brand;

use Livewire\Component;
use App\Models\Brand;
use Livewire\WithFileUploads;
use \Str;

class Create extends Component
{
    use WithFileUploads;
    public $name = "";
    public $slug = "";
    public $image;

    public function updatingName($value) {
        $this->slug = Str::slug($value);
    }
    public function save() {

        $this->validate([
            'name'=> "required",
            "slug"=>'required|unique:brands,slug|string|max:255',
            "image"=> "nullable|image|mimes:jpeg,png,jpg,gif|max:2048"
        ]);

        if(!empty($this->image)) {
            $this->image = $this->image->store('brands','public');
        }

        

        Brand::create([
            'name'=> $this->name,
            "slug"=> $this->slug,
            "image"=> $this->image
        ]);
        
        $this->reset(['name','slug','image']);
        return $this->redirectRoute('admin.brand',navigate:true);
    }

    public function render()
    {
        return view('livewire.admin.brand.create')->layout('layouts.admin');
    }
}
