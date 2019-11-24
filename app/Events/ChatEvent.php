<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $survey_id;
    public $chat_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($survey_id, $chat_id)
    {
        $this->survey_id = $survey_id;
        $this->chat_id = $chat_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('chat.'.$this->survey_id);
    }

    public function broadcastAs()
    {
        return 'chat.send';
    }
}
