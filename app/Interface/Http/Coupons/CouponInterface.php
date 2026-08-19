<?php

namespace App\Interface\Http\Coupons;

use App\Models\Coupon;

interface CouponInterface
{
    public function getAllPaginated(int $perPage = 10);

    public function create(array $data): Coupon;

    public function update(Coupon $Coupon, array $data): void;

    public function delete(Coupon $Coupon);
}
