<?php

namespace App\Listeners;

use App\Events\RegisteredLineMemberFirst;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LineMemberMessageListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param RegisteredLineMemberFirst $event
     * @return void
     */
    public function handle(RegisteredLineMemberFirst $event)
    {
        $event->line_member->sendEmailVerificationNotification();
    }
}
