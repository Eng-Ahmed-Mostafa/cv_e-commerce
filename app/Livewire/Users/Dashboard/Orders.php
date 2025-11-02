<?php

namespace App\Livewire\Users\Dashboard;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Orders extends Component
{
    public function render()
    {
        $orders = Order::where('user_id',Auth::id())->get();
        return view('livewire.users.dashboard.orders',compact('orders'))->layout('layouts.user');
    }
}
