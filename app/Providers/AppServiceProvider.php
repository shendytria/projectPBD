<?php

namespace App\Providers;

use App\Models\MenuModel;
use App\Models\MenuUserModel;
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
        // $navbar = MenuModel::all();

        // $auth = Auth::user()->jenisUser_id;

        // $navbar = MenuUserModel::with('menu')->where('jenisUser_id', $auth)->get();
        // View::share('navbar', $navbar);
    }
}
