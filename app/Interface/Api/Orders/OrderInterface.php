<?php

namespace App\Interface\Api\Orders;

use App\Models\Order;
use Illuminate\Http\UploadedFile;

interface OrderInterface
{
    public function getAllOrdersPaginated();

    public function getAllOrdersWithDetails(int $id);
}
