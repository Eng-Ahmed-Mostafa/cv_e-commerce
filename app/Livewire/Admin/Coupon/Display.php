<?php

namespace App\Livewire\Admin\Coupon;

use App\Models\Coupon;
use Livewire\Component;
use Livewire\WithPagination;

class Display extends Component
{
    use WithPagination;

    public function deleteCoupon($id)
    {
        $coupon = Coupon::find($id);
        if ($coupon) {
            $coupon->delete();
        }
    }
    public function render()
    {
        $coupons = Coupon::paginate(10);
        return view('livewire.admin.coupon.display',compact('coupons'))->layout('layouts.admin');
    }
}
