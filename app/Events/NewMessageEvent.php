<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;


class NewMessageEvent implements ShouldBroadcast
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
        Log::info('Constructor');
        $this->chatGroupId = $chatGroupId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return  ['chat-group.' . $this->chatGroupId];
    }

    public function broadcastAs()
    {
        return 'new-message.' . $this->chatGroupId;
    }
}
