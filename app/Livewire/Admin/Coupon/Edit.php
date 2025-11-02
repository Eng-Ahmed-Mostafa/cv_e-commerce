<?php

namespace App\Livewire\Admin\Coupon;

use App\Models\Coupon;
use Livewire\Component;

class Edit extends Component
{
    public $code;
    public $type;
    public $value;
    public $cart_value;
    public $expiry_date;

    public $coupon;
    public function mount($coupon)
    {
        $this->coupon = Coupon::findOrFail($coupon);
        $this->code = $this->coupon->code;
        $this->type = $this->coupon->type;
        $this->value = $this->coupon->value;
        $this->cart_value = $this->coupon->cart_value;
        $this->expiry_date = $this->coupon->expiry_date;
    }
    public function save() {
        $this->validate([
            'code' => 'required|unique:coupons,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'cart_value' => 'required|numeric|min:0',
            'expiry_date' => 'required|date|after:today',
        ]);


        $this->coupon->update([
            'code' => $this->code,
            'type' => $this->type,
            'value' => $this->value,
            'cart_value' => $this->cart_value,
            'expiry_date' => $this->expiry_date,
        ]);


        $this->reset(['code', 'type', 'value', 'cart_value', 'expiry_date']);
        
        $this->redirectRoute('admin.coupon', navigate:true);
    }  

    public function render()
    {
        return view('livewire.admin.coupon.edit')->layout('layouts.admin');
    }
}
