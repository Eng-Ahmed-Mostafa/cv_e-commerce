<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['order_items', 'detail'])->get();

        if ($orders->isEmpty()) {
            return $this->errorResponse('orders not found', 404);
        }

        return $this->successResponse(OrderResource::collection($orders), 'Orders retrieved successfully');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::where('id', $id)->with(['order_items', 'detail'])->first();
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

        $order = Order::where('id', $id)->first();

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


}
