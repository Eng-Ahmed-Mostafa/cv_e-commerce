<?php

namespace App\Livewire\Admin\Order;

use App\Models\User;
use App\Models\Product;
use Livewire\Component;

class Details extends Component
{
    public $users;
    public $products;

    public function mount()
    {
        $this->users = User::with('orders')->get();
        $this->products = Product::with('orders')->get();
    }
    public function render()
    {
        
        return view('livewire.admin.order.details');
    }
}
