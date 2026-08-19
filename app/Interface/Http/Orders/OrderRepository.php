<?php

namespace App\Interface\Http\Orders;

use App\Models\Order;


class OrderRepository implements OrderInterface
{
    public function getAllOrdersPaginated()
    {
        return Order::with([
            'detail',
            'order_items',
        ])->paginate(10);
    }

    public function getAllOrdersWithDetails(int $id)
    {
        return Order::with([
            'detail',
            'order_items.product.category',
            'order_items.product.brand',
        ])->findOrFail($id);
    }
}
