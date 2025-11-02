<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    public $fillable = [
        'full_name',
        'phone',
        'pincode',
        'state',
        'town',
        'city',
        'no_building',
        'area',
        'landmark',
        'order_id',
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

}
