<?php

namespace App\Livewire\Users;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Detail;
use App\Models\OrderItem;
use Livewire\Component;
use App\Models\ShippingDetails;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class Checkout extends Component
{
    public $shippingdeltails;
    public $full_name;
    public $phone;
    public $pincode;
    public $state;
    public $town;
    public $no_building;
    public $area;
    public $landmark;
    public $cart;
    public $subtotal;
    public $total;

    public function mount() {
        $this->cart = Cart::where('user_id',Auth::id())->orWhere('session_id',session()->id())->with('items.product')->first();
        
    }

    public function save() {
        $this->validate([
            'full_name' => 'required',
            'phone' => 'required',
            'pincode' => 'required',
            'state' => 'required',
            'town' => 'required',
        ]);

        $order = Order::create([
            'subtotal' => session('total'),
            'discount' => $this->cart->discount_value ?? 0,
            'tax' => 19,
            'total' => $this->cart->total,
            'status' => 'ordered',
            'total_amount' => $this->collectTotal(),
            'ordered_date' => Date::now(),
            'user_id' => Auth::id(),
        ]);
        Detail::create([
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'pincode' => $this->pincode,
            'state' => $this->state,
            'town' => $this->town,
            'no_building' => $this->no_building,
            'area' => $this->area,
            'landmark' => $this->landmark,
            'order_id' => $order->id
        ]);
        foreach($this->cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product->id,
                'quantity' => $item->quantity,
                'price' => $item->price
            ]);
        }

        session()->flash('messafe','ordered success');
        $this->redirectRoute('confirmation',navigate:true);
    
    }

    public function collectTotal()
    {
        return $this->cart->total + ($this->cart->total / 19);
    }

    public function render()
    {
        return view('livewire.users.checkout')->layout('layouts.user');
    }
}
