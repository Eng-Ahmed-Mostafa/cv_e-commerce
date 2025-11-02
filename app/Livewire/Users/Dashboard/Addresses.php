<?php

namespace App\Livewire\Users\Dashboard;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Addresses extends Component
{
    public function render()
    {
        $order = Order::where('user_id',Auth::id())->with('detail')->first();
        return view('livewire.users.dashboard.addresses',compact('order'))->layout('layouts.user');
    }
}
