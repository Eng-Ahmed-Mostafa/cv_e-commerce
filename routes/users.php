<?php

use App\Livewire\Users\Cart;
use App\Livewire\Users\Checkout;
use App\Livewire\Users\Confirmation;
use App\Livewire\Users\Dashboard\Addresses;
use App\Livewire\Users\Dashboard\Orders;
use App\Livewire\Users\Dashboard\Index;
use App\Livewire\Users\Home;
use App\Livewire\Users\Shop;
use App\Livewire\Users\Details;

use Illuminate\Support\Facades\Route;

Route::get('/',Home::class)->name('home');

Route::middleware(['auth'])->group(function() {
    
});
Route::get('shop',Shop::class)->name('shop');
Route::get('details/{product}',Details::class)->name('details');
Route::get('cart',Cart::class)->name('cart');
Route::get('user/checkout',Checkout::class)->name('user.checkout');
Route::get('confirmation', Confirmation::class)->name('confirmation');


Route::get('addresses',Addresses::class)->name('addresses');
Route::get('orders',Orders::class)->name('orders');
Route::get('dashboard',Index::class)->name('dashboard');