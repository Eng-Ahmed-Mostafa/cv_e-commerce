<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;
    public function render()
    {
        $orders = Order::with(['user','order_items'])->paginate(10);
        return view('livewire.admin.dashboard',compact('orders'))->layout('layouts.admin');
    }
}
