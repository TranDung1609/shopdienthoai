<?php

namespace App\Providers;
use App\Product;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*',function($view){

            $min_price = Product::min('product_price');
            $max_price = Product::max('product_price');

            $min_price_r = $min_price - 1000000;
            $max_price_r = $max_price + 10000000;
            
        $view->with('min_price',$min_price)->with('max_price',$max_price)->with('max_price_r',$max_price_r)->with('min_price_r',$min_price_r);
        });
        
    }
}
