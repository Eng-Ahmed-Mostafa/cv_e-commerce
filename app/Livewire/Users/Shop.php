<?php

namespace App\Livewire\Users;

use App\Models\Cart;
use App\Models\Brand;
use App\Models\Product;
use Livewire\Component;
use App\Models\CartItem;
use App\Models\Category;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Shop extends Component
{
    use WithPagination;

    public $categories;

    public $brands;

    public function mount()
    {
        $this->categories = Category::get();
        $this->brands = Brand::with('products')->get();
    }

    public function addToCart($productId)
    {
        $userId = Auth::id();

        $cart = Cart::with('items')->where('user_id', $userId)
            ->where('status', 'active')
            ->first();

        if (! $cart) {
            $cart = Cart::create([
                'user_id' => $userId,
                'total' => 0,
                'status' => 'active',
                'session_id' => session()->getId(),
            ]);
        }

        $product = Product::findOrFail($productId);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if($item) {
            $item->quantity += 1;
            $item->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->sale_price ?? $product->price,
            ]);
        }

        $cart->total = $cart->items->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $cart->save();

        session()->flash('success', 'Product added to cart!');
    }

    public function render()
    {
        $products = Product::with('category')->paginate(9);

        return view('livewire.users.shop', compact('products'))->layout('layouts.user');
    }
}
