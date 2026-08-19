<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Interface\Http\Categories\CategoryInterface::class,
            \App\Interface\Http\Categories\CategoryRepository::class
        );
        $this->app->bind(
            \App\Interface\Api\Categories\CategoryInterface::class,
            \App\Interface\Api\Categories\CategoryRepository::class
        );
        $this->app->bind(
            \App\Interface\Http\Brands\BrandInterface::class,
            \App\Interface\Http\Brands\BrandRepository::class
        );
        $this->app->bind(
            \App\Interface\Api\Brands\BrandInterface::class,
            \App\Interface\Api\Brands\BrandRepository::class
        );
        $this->app->bind(
            \App\Interface\Http\Products\ProductInterface::class,
            \App\Interface\Http\Products\ProductRepository::class
        );
        $this->app->bind(
            \App\Interface\Api\Products\ProductInterface::class,
            \App\Interface\Api\Products\ProductRepository::class
        );
        $this->app->bind(
            \App\Interface\Http\Coupons\CouponInterface::class,
            \App\Interface\Http\Coupons\CouponRepository::class
        );
        $this->app->bind(
            \App\Interface\Api\Coupons\CouponInterface::class,
            \App\Interface\Api\Coupons\CouponRepository::class
        );
        $this->app->bind(
            \App\Interface\Http\Orders\OrderInterface::class,
            \App\Interface\Http\Orders\OrderRepository::class
        );
        $this->app->bind(
            \App\Interface\Api\Orders\OrderInterface::class,
            \App\Interface\Api\Orders\OrderRepository::class
        );
        $this->app->bind(
            \App\Interface\Http\Users\UserInterface::class,
            \App\Interface\Http\Users\UserRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
