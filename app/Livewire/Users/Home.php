<?php

namespace App\Livewire\Users;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $categories = Category::all();
        $products = Product::limit(8)->get();

        return view('livewire.users.home',compact('categories','products'))->layout('layouts.user');
    }
}
