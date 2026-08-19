<?php

namespace App\Interface\Api\Orders;

interface OrderInterface
{
    public function getAllOrdersPaginated();

    public function getAllOrdersWithDetails(int $id);
}
