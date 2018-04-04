<?php

namespace App\Events;

use App\Conversation;
use App\Message;
use App\Transformers\MessageTransformer;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Broadcast;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    private $conversation;
    private $messageModel;

    public $message;


    /**
     * Create a new event instance.
     *
     * @param Message $message
     * @param Conversation $conversation
     */
    public function __construct(Message $message, Conversation $conversation)
    {
        $this->messageModel = $message;
        $this->message = MessageTransformer::transform($message);
        $this->conversation = $conversation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('conversation.' . $this->conversation->public_id);
    }
}
