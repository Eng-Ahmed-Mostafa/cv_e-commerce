<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'discount',
        'tax',
        'total',
        'status',
        'total_amount',
        'ordered_date',
        'delivered_date',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function detail()
    {
        return $this->hasOne(Detail::class, 'order_id');
    }

    public function order_items() {
        return $this->hasMany(OrderItem::class);
    }
    protected $casts = [
        'ordered_date' => 'datetime',
        'delivered_date' => 'datetime',
    ];
}
