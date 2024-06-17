<?php

namespace App\Providers;

use App\Models\cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        View::composer('*', function($view) {

             // for all product
             $products = Product::orderBy('created_at', 'desc')->paginate(10);
             $view->with('products', $products);

            // category links used the home page header

            $categoryLinks = Category::all();
            $view->with('categoryLinks', $categoryLinks);



            if(Auth::user()){
                $userId = Auth::user()->id;
                $cartCount = cart::where('userId', $userId)->count();
                $view->with('cartCount', $cartCount);



            }

        });
    }



   
}
