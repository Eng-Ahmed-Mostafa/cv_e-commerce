<?php

namespace App\Livewire\Admin\Order;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class Display extends Component
{
    use WithPagination;
    public function render()
    {
        $orders = Order::paginate(10);
        return view('livewire.admin.order.display',compact('orders'))->layout('layouts.admin');
    }
}
