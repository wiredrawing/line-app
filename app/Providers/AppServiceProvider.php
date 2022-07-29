<?php

namespace App\Providers;

use App\Interfaces\LineLoginInterface;
use App\Repositories\LineLoginRepository;
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
        // 独自リポジトリパターンの追加
        $this->app->bind(LineLoginInterface::class, LineLoginRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
