<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CrossOrigin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
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
