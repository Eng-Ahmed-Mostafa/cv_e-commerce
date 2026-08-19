<?php

namespace App\Interface\Http\Orders;

use App\Models\Order;

interface OrderInterface
{
    public function getAllOrdersPaginated();
    public function getAllOrdersWithDetails(int $id);
}
