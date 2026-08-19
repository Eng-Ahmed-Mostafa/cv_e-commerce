<?php

namespace App\Interface\Http\Coupons;

use App\Models\Coupon;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CouponRepository implements CouponInterface
{
    public function getAllPaginated(int $perPage = 10)
    {
        return Coupon::paginate($perPage);
    }

    public function create(array $data): Coupon
    {
        return Coupon::create($data);
    }

    public function update(Coupon $Coupon, array $data): void
    {
        $Coupon->update($data);
    }

    public function delete(Coupon $Coupon)
    {
        $Coupon->delete();
    }
}
