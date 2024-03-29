<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            // -------------------------------------
            // 管理画面側のルーティング設定をadminディレクトリに
            // 切り分ける
            // -------------------------------------
            // 非APIルーティング
            Route::prefix("admin")
                ->middleware("check.login.admin")
                ->middleware("web")
                ->namespace($this->namespace)
                ->group(base_path("routes/admin/web.php"));
            // 管理画面側APIのルーティング
            Route::prefix("admin/api")
                ->middleware('api')
                ->middleware("check.login.admin")
                ->namespace($this->namespace)
                ->group(base_path("routes/admin/api.php"));

            // フロントエンド側APIのルーティング
            Route::group(["prefix" => "front", "as" => "front."], function () {
                Route::group(["prefix" => "api", "as" => "api."], function () {
                    Route::middleware('api')
                        ->namespace($this->namespace)
                        ->group(base_path("routes/front/api.php"));
                });
            });
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
