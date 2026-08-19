<?php

namespace App\Interface\Api\Coupons;

use App\Models\Coupon;
use Illuminate\Http\UploadedFile;

interface CouponInterface
{
    public function getAllPaginated(int $perPage = 10);
    public function getAll();
    public function create(array $data);
    public function findCoupon($id);
    public function update(Coupon $coupon, array $data);
    public function delete(Coupon $coupon);
}
