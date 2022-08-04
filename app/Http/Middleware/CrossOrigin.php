<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CrossOrigin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        // 独自のhttpヘッダーを返却する
        $response->header("Original-Header", "wire-drawing.co.jp");
        // cors対応
        $response->header("Access-Control-Allow-Origin", "http://localhost:3001");
        $response->header("Access-Control-Allow-Credentials", "true");
        $response->header("Access-Control-Allow-Methods", "GET,HEAD,PUT,PATCH,POST,DELETE");
        $response->header("Access-Control-Allow-Headers", 'access-control-allow-origin,content-type');
        return $response;
    }
}
