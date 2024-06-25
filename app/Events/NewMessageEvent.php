<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatGroupId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($chatGroupId)
    {
        $this->chatGroupId = $chatGroupId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return ['chat-group.' . $this->chatGroupId];
    }

    public function broadcastAs()
    {
        return 'new-message.' . $this->chatGroupId;
    }
}
