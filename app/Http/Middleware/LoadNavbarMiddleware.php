<?php

namespace App\Http\Middleware;

use App\Models\MenuUserModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class LoadNavbarMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $navbar = MenuUserModel::with('menu')->where('jenisUser_id', Auth::user()->jenisUser_id)->get();
        View::share('navbar', $navbar);

        return $next($request);
    }
}
    