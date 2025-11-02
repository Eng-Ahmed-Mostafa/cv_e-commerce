<?php

namespace App\Livewire\Users;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Confirmation extends Component
{
    public function render()
    {
        $order =  Order::where('user_id',Auth::id())->with(['detail','order_items'])->first();
        return view('livewire.users.confirmation',compact('order'))->layout('layouts.user');
    }
}