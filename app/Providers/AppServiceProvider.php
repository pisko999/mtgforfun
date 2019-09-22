<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Schema::defaultStringLength(1023);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(
            'App\Repositories\ProductRepositoryInterface',
            'App\Repositories\ProductRepository'
        );

        $this->app->bind(
            'App\Repositories\BoosterRepositoryInterface',
            'App\Repositories\BoosterRepository'
        );

        $this->app->bind(
            'App\Repositories\BoosterBoxRepositoryInterface',
            'App\Repositories\BoosterBoxRepository'
        );

        $this->app->bind(
            'App\Repositories\CategoryRepositoryInterface',
            'App\Repositories\CategoryRepository'
        );

        $this->app->bind(
            'App\Repositories\EditionRepositoryInterface',
            'App\Repositories\EditionRepository'
        );

        $this->app->bind(
            'App\Repositories\CardRepositoryInterface',
            'App\Repositories\CardRepository'
        );

        $this->app->bind(
            'App\Repositories\StockRepositoryInterface',
            'App\Repositories\StockRepository'
        );

        $this->app->bind(
            'App\Repositories\CommandRepositoryInterface',
            'App\Repositories\CommandRepository'
        );

        $this->app->bind(
            'App\Repositories\CartRepositoryInterface',
            'App\Repositories\CartRepository'
        );

        $this->app->bind(
            'App\Repositories\PaymentRepositoryInterface',
            'App\Repositories\PaymentRepository'
        );

        $this->app->bind(
            'App\Repositories\ItemRepositoryInterface',
            'App\Repositories\ItemRepository'
        );

        $this->app->bind(
            'App\Repositories\PriceRepositoryInterface',
            'App\Repositories\PriceRepository'
        );

        $this->app->bind(
            'App\Repositories\AddressRepositoryInterface',
            'App\Repositories\AddressRepository'
        );
    }
}
