<?php

namespace App\Livewire\Admin\Brand;

use App\Models\Brand;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class Display extends Component
{
    use WithPagination;

    public function deleteBrand($id) {

        $brand = Brand::find($id);
        if(!empty($brand->image)) {
                Storage::disk('public')->delete($brand->image);
        }
        $brand->delete();
        session()->flash('massage','Deleted successifly');

    }
    public function render()
    {
        $brands = Brand::paginate(10);
        return view('livewire.admin.brand.display',compact('brands'))->layout('layouts.admin');
    }
}
