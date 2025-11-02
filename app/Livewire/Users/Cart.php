<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\CartItem;
use App\Models\Cart as CartModel;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;

class Cart extends Component
{
    public $cart;
    public $tax = 19;
    public $total;
    public $quantities = [];
    public $subtotals = [];
    public $coupon;
    public $discount = 0;

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cart = CartModel::where('user_id', Auth::id())
            ->orWhere('session_id', session()->id())
            ->with('items.product')
            ->first();

        if (!$this->cart) {
            $this->cart = CartModel::create([
                'user_id' => Auth::id(),
                'session_id' => session()->id(),
                'total' => 0,
            ]);
        }

        foreach ($this->cart->items as $item) {
            $this->quantities[$item->id] = $item->quantity;
            $this->updateSubtotal($item->id, $item->product->sale_price ?? $item->product->price);
        }

        $this->discount = $this->cart->discount_value ?? 0;
        $this->calculateTotal();
    }

    public function increment($itemId)
    {
        $item = CartItem::find($itemId);
        if ($item && $this->quantities[$itemId] < $item->product->quantity) {
            $this->quantities[$itemId]++;
            $this->updateSubtotal($itemId, $item->product->sale_price ?? $item->product->price);
            $item->update([
                'quantity' => $this->quantities[$itemId],
                'price' => $this->subtotals[$itemId],
            ]);
            $this->calculateTotal();
        }
    }

    public function decrement($itemId)
    {
        $item = CartItem::find($itemId);
        if ($item && $this->quantities[$itemId] > 1) {
            $this->quantities[$itemId]--;
            $this->updateSubtotal($itemId, $item->product->sale_price ?? $item->product->price);
            $item->update([
                'quantity' => $this->quantities[$itemId],
                'price' => $this->subtotals[$itemId],
            ]);
            $this->calculateTotal();
        }
    }

    public function calculateTotal()
    {
        $this->cart->refresh();
        $subtotal = $this->cart->items->sum(fn($item) => $item->sale_price ?? $item->price);
        $discount = $this->cart->discount_value ?? 0;

        $this->cart->total = max(0, $subtotal - $discount);
        $this->total = $subtotal;
        $this->cart->save();
    }

    public function checkCoupon()
    {
        $coupon = Coupon::where('code', $this->coupon)->first();

        if (!$coupon) {
            session()->flash('faild', 'Coupon not found');
            return;
        }

        $subtotal = $this->cart->items->sum(fn($item) => $item->price);
        $discount = $coupon->type === 'fixed'
            ? $coupon->value
            : ($subtotal * $coupon->value) / 100;

        $discount = min($discount, $subtotal);

        $this->cart->coupon_id = $coupon->id;
        $this->total = $subtotal;
        
        $this->cart->discount_value = $discount;
        $this->discount = $this->cart->discount_value;
        $this->cart->total = $subtotal - $discount;
        $this->cart->save();
        // $this->cart = $this->cart->fresh(['items.product']);

        session()->flash('success', 'Coupon applied successfully');
    }

    public function updateSubtotal($itemId, $price)
    {
        $this->subtotals[$itemId] = $this->quantities[$itemId] * $price;
    }


    public function checkout() {
        session()->flash('total', $this->total);
        return $this->redirectRoute('user.checkout', navigate: true);
    }

    public function render()
    {
        return view('livewire.users.cart', [
            'cart' => $this->cart,
            'discount' => $this->discount,
        ])->layout('layouts.user');
    }
}
