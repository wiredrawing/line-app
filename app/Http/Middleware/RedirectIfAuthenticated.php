<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    ///**
    // * Handle an incoming request.
    // *
    // * @param  \Illuminate\Http\Request  $request
    // * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
    // * @param  string|null  ...$guards
    // * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
    // */
    //public function handle(Request $request, Closure $next, ...$guards)
    //{
    //    $guards = empty($guards) ? [null] : $guards;
    //
    //    foreach ($guards as $guard) {
    //        if (Auth::guard($guard)->check()) {
    //            return redirect(RouteServiceProvider::HOME);
    //        }
    //    }
    //
    //    return $next($request);
    //}

    /**
     * Handle an incoming request.
     * 認証処理をカスタマイズして,ログイン後は独自のルーティングに遷移させる
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param string|null ...$guards
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(route("admin.line.account.index"));
            }
        }

        return $next($request);
    }
}
