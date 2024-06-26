<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
        Log::info('Este es un mensaje: ' . 'chat-group.' . $this->chatGroupId);
        return ['chat-group.' . $this->chatGroupId];
    }

    public function broadcastAs()
    {
        Log::info('Este es un mensaje: ' . 'new-message.' . $this->chatGroupId);
        return 'new-message.' . $this->chatGroupId;
    }
}
