<?php

namespace App\Livewire\Admin\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class Display extends Component
{
    use WithPagination;
    public function deleteProduct($id)
    {
        $product = Product::find($id);
        if (!empty($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        if (!empty($product->images)) {

            foreach (explode(',', $product->images) as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        $product->delete();
        session()->flash('message', 'Product Deleted Successfully');
    }
    public function render()
    {
        $products = Product::paginate(10);
        return view('livewire.admin.product.display', compact('products'))->layout('layouts.admin');
    }


}
