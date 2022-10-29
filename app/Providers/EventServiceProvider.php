<?php

namespace App\Providers;

use App\Events\RegisteredLineMemberFirst;
use App\Listeners\LineMemberMessageListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Registered::class => [
        //     SendEmailVerificationNotification::class,
        // ],
        // 初めてのLINEログイン成功時,当該チャネルのタイムラインにログイン成功のメッセージをpushする
        RegisteredLineMemberFirst::class => [
            // --------------------------------------------------------------
            // マッピングするクラスはhandleメソッドを実装したクラスである必要がある
            // --------------------------------------------------------------
            LineMemberMessageListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
