<?php

namespace App\Events;

use App\Models\LineMember;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * @property $line_memer
 */
class RegisteredLineMemberFirst
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $line_member = null;

    /**
     * Create a new event instance.
     *
     * @param LineMember $line_member
     */
    public function __construct(LineMember $line_member)
    {
        // プロパティにLineMemberオブジェクトを設定
        $this->line_member = $line_member;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
