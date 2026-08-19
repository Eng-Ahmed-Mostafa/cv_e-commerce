<?php

namespace App\Interface\Api\Orders;

use App\Models\Order;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
        ])->find($id);
    }
}
