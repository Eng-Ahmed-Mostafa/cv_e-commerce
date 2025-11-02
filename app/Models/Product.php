<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    public $fillable = [
        'name',
        'slug',
        'price',
        'image',
        'images',
        'short_description',
        'description',
        'sale_price',
        'SKU',
        'feature',
        'quantity',
        'stock',
        'category_id',
        'brand_id'
    ];


    public function category() {
        return $this->belongsTo(Category::class);
    }
    
    public function brand() {
        return $this->belongsTo(Brand::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items() {
        return $this->hasMany( CartItem::class);
    }

    public function order_item()
    {
        return $this->belongsTo(OrderItem::class);
    }




}
