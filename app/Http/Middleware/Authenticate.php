<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    ///**
    // * Get the path the user should be redirected to when they are not authenticated.
    // *
    // * @param  \Illuminate\Http\Request  $request
    // * @return string|null
    // */
    //protected function redirectTo($request)
    //{
    //    if (! $request->expectsJson()) {
    //        return route('login');
    //    }
    //}


    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * 認証用テーブルを users => administratorsに変更した場合などデフォルトの設定から変更
     *
     * @param $request
     * @return string|void|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('admin.login.index');
        }
    }
}
