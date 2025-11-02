<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cart = Cart::with('items.product')->when(Auth::check(), function ($q) {
                $q->where('user_id', Auth::id());
            }, function ($q) {
                $q->where('session_id', session()->id());
            })->first();


        if(!$cart) {
            return $this->errorResponse('Cart not found',404);
        }
        
        return $this->successResponse(new CartResource($cart), 'Cart retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'nullable|integer|min:1'
            ]);
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed',422,$e->errors());
        }
        
        $cart = Cart::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'status' => 'active'
            ],
            [
                'total' => 0,
                'discount_value' => 0
            ]
        );

        $product = Product::find($request->product_id);
        if(!$product) {
            return $this->errorResponse('Product not found',404);
        }
        
        $item = CartItem::where('cart_id',$cart->id)->where('product_id',$request->product_id)->first();

        if($item) {
            $item->quantity += $request->quantity ?? 1;
            $item->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity ?? 1,
                'price' => $product->sale_price ?? $product->price
            ]);
        }

        $cart->load('items.product');
        $cart->total = $cart->items->sum(fn($item) => ($item->sale_price ?? $item->price) * $item->quantity);
        $cart->save();
        return $this->successResponse(new CartResource($cart), 'Cart created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed',422,$e->errors());
        }

        $item = CartItem::find($id);
        if (!$item) {
            return $this->errorResponse('Item not found', 404);
        }

        $item->quantity = $request->quantity;
        $item->save();


        $cart = $item->cart->load('items.product');
        $cart->total = $cart->items->sum(fn($item) => ($item->sale_price ?? $item->price) * $item->quantity);
        $cart->save();

        return $this->successResponse(new CartResource($cart),'Cart updated success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = CartItem::find($id);
        if (!$item) {
            return $this->errorResponse('Item not found', 404);
        }

        $cart = $item->cart;
        $item->delete();

        $cart->load('items.product');
        $cart->total = $cart->items->sum(fn($item) => $item->price * $item->quantity) ?? 0;
        $cart->save();

        return $this->successResponse( new CartResource($cart->load('items.product')),'Cart deleted success');
    }
}
