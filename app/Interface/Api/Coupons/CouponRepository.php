<?php

namespace App\Interface\Api\Coupons;

use App\Models\Coupon;

class CouponRepository implements CouponInterface
{
    public function getAllPaginated(int $perPage = 10)
    {
        return Coupon::paginate($perPage);
    }

    public function getAll()
    {
        return Coupon::all();
    }

    public function create(array $data)
    {
        $coupon = Coupon::create([
            'code' => $data['code'],
            'type' => $data['type'],
            'value' => $data['value'],
            'cart_value' => $data['cart_value'],
            'expiry_date' => $data['expiry_date'],
        ]);
        return $coupon;
    }

    public function findCoupon($id)
    {
        return Coupon::find($id);
    }

    public function update(Coupon $coupon, array $data)
    {
        $coupon->update([
            'code' => $data['code'],
            'type' => $data['type'],
            'value' => $data['value'],
            'cart_value' => $data['cart_value'],
            'expiry_date' => $data['expiry_date']
        ]);
    }

    public function delete(Coupon $coupon)
    {
        $coupon->delete();
    }
}
