<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $chatRoomId;
    public Message $message;

    public function __construct(int $chatRoomId, Message $message)
    {
        $this->chatRoomId = $chatRoomId;
        $this->message    = $message;
    }

    public function broadcastOn(): Channel
    {
        return new Channel("chat.{$this->chatRoomId}");
    }

    public function broadcastAs(): string
    {
        return 'message-sent';
    }

    public function broadcastWith(): array
    {
        return [
            'id'         => $this->message->id,
            'user'       => $this->message->user->name,
            'user_id'    => $this->message->user_id,
            'body'       => $this->message->body,
            'created_at' => $this->message->created_at->toTimeString(),
        ];
    }
}
