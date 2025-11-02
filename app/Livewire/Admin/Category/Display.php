<?php

namespace App\Livewire\Admin\Category;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class Display extends Component
{
    use WithPagination;

    public function deleteCategory($id) {

        $category = Category::find($id);
        if(!empty($category->images)) {

            foreach(json_decode($category->images,true) as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        $category->delete();
        session()->flash('massage','Deleted successifly');

    }
    public function render()
    {
        $categories = Category::paginate(10);
        return view('livewire.admin.category.display',compact('categories'))->layout('layouts.admin');
    }
}
