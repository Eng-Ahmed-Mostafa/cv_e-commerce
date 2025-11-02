<?php

namespace App\Livewire\Users;

use App\Models\Product;
use Livewire\Component;

class Details extends Component
{
    public $product;
    public $name;
    public $slug;
    public $short_description;
    public $description;
    public $image;
    public $images;
    public $price;
    public $sale_price;
    public $SKU;
    public $feature;
    public $quantity;
    public $stock;



    public function mount($product) {
        $this->product = Product::with(['brand','category'])->find($product);
        $this->quantity = $this->product->quantity;
    }
    public function render()
    {
        $products = Product::limit(8)->get();
        return view('livewire.users.details',compact('products'))->layout('layouts.user');
    }
}
