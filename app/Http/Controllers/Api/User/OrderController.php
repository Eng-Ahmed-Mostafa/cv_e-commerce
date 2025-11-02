<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Detail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\PaymobService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['order_items', 'detail'])->where('user_id', Auth::id())->get();

        if ($orders->isEmpty()) {
            return $this->errorResponse('orders not found', 404);
        }

        return $this->successResponse(OrderResource::collection($orders), 'Orders retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'full_name' => 'required',
                'phone' => 'required',
                'pincode' => 'required',
                'state' => 'required',
                'town' => 'required',
                'city' => 'nullable',
                'no_building' => 'nullable',
                'area' => 'nullable',
                'landmark' => 'nullable',
            ]);
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        }

        $cart = Cart::where('user_id', Auth::id())->with('items.product')->first();

        if (! $cart || $cart->items->isEmpty()) {
            return $this->errorResponse('Cart is empty', 400);
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'discount' => $cart->discount_value ?? 0,
            'tax' => 19,
            'total' => $cart->total,
            'status' => 'ordered',
            'total_amount' => $cart->total + 19,
            'ordered_date' => Date::now(),
        ]);

        foreach ($cart->items as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
            ]);
        }

        Detail::create([
            'order_id' => $order->id,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'pincode' => $request->pincode,
            'state' => $request->state,
            'town' => $request->town,
            'city' => $request->city,
            'no_building' => $request->no_building,
            'area' => $request->area,
            'landmark' => $request->landmark,
        ]);

        $order->load('order_items.product', 'detail');
        $order->total = $order->order_items->sum(fn ($item) => $item->price * $item->quantity);
        $order->total_amount = $order->total + $order->tax - $order->discount;
        $order->save();

        $cart->items()->delete();
        $cart->total = 0;
        $cart->save();

        return $this->successResponse(new OrderResource($order->load('order_items.product', 'detail')), 'Order created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::where('user_id', Auth::id())->where('id', $id)->with(['order_items', 'detail'])->first();
        if (! $order) {
            return $this->errorResponse('Order not found', 404);
        }

        return $this->successResponse(new OrderResource($order));
    }

    public function lastOrder()
    {
        $order = Order::where('user_id', Auth::id())->with(['order_items', 'detail'])->latest()->first();
        if (! $order) {
            return $this->errorResponse('Order not found', 404);
        }

        return $this->successResponse(new OrderResource($order));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'full_name' => 'required',
                'phone' => 'required',
                'pincode' => 'required',
                'state' => 'required',
                'town' => 'required',
                'city' => 'nullable',
                'no_building' => 'nullable',
                'area' => 'nullable',
                'landmark' => 'nullable',
            ]);
        } catch (ValidationException $e) {
            return $this->errorResponse('Validation failed', 422, $e->errors());
        }

        $order = Order::where('user_id', Auth::id())->where('id', $id)->first();

        $detail = $order->detail;

        $detail->update([
            'order_id' => $order->id,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'pincode' => $request->pincode,
            'state' => $request->state,
            'town' => $request->town,
            'city' => $request->city,
            'no_building' => $request->no_building,
            'area' => $request->area,
            'landmark' => $request->landmark,
        ]);

        return $this->successResponse(new OrderResource($order->load('order_items', 'detail')), 'Order updated success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::find($id);
        if (! $order) {
            return $this->errorResponse('Order not found', 404);
        }

        if ($order->detail) {
            $order->detail->delete();
        }

        $order->delete();

        return $this->successResponse(null, 'Order deleted successfully');

    }

    public function pay($orderId, PaymobService $paymob)
    {
        $user = Auth::user();
        $order = Order::where('id', $orderId)->where('user_id', $user->id)->first();

        if (! $order) {
            return $this->errorResponse('Order Not Found', 403);
        }

        $authToken = $paymob->authenticate();

        try {
            $paymobOrderId = $paymob->registerOrder($authToken, $order->total * 100, $order->id);
        } catch (\Exception $e) {
            return $this->errorResponse('Paymob Order Error: '.$e->getMessage(), 500);
        }

        $billing = [
            'apartment' => 'NA',
            'email' => $user->email ?? 'no-email@example.com',
            'floor' => 'NA',
            'first_name' => $user->name ?? 'User',
            'street' => 'NA',
            'building' => 'NA',
            'phone_number' => $user->phone ?? '01000000000',
            'shipping_method' => 'NA',
            'postal_code' => 'NA',
            'city' => 'Cairo',
            'country' => 'EG',
            'last_name' => 'User',
            'state' => 'NA',
        ];

        $paymentToken = $paymob->getPaymentKey($authToken, $order->total * 100, $paymobOrderId, $billing);

        if (! $paymentToken) {
            return $this->errorResponse('Failed to generate Paymob payment key', 500);
        }

        $iframeId = env('PAYMOB_IFRAME_ID');
        $paymentUrl = "https://accept.paymob.com/api/acceptance/iframes/$iframeId?payment_token=$paymentToken";

        return $this->successResponse(['url' => $paymentUrl], 'Payment link created successfully');
    }

    public function paymentCallback(Request $request)
    {

        $order = Order::find($request->obj['order']['merchant_order_id']);

        if ($request->obj['success'] && $order) {
            $order->update(['status' => 'paiding']);
        }

        return response()->json(['message' => 'Your pay done!']);
    }
}
