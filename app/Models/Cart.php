<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use League\CommonMark\Node\Query\OrExpr;

class Cart extends Model
{
    use HasFactory;
    public $fillable = ['user_id', 'total', 'status', 'session_id','coupon_id','discount_value'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function coupons() {
        return $this->hasMany(Coupon::class);
    }

}
