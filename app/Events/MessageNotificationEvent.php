<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageNotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatGroup;
    private $userIds;

    public function __construct($chatGroup, $userIds)
    {
        $this->chatGroup = $chatGroup;
        $this->userIds = $userIds;
    }

    public function broadcastOn()
    {
        $channels = [];
        foreach ($this->userIds as $userId) {
            $channels[] = new Channel('user-notifications.' . $userId);
        }
        return $channels;
    }

    public function broadcastAs()
    {
        return 'new-notification';
    }

    public function broadcastWith()
    {
        return [
            'chatGroupId' => $this->chatGroup->id,
            'name' => $this->chatGroup->name, // Opcional: enviar más datos según lo necesario
            'description' => $this->chatGroup->description // Opcional
        ];
    }
}