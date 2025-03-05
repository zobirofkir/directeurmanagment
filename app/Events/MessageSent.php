<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load(['sender', 'receiver']);
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->message->receiver_id);
    }

    public function broadcastWith()
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'content' => $this->message->content,
                'created_at' => $this->message->created_at,
                'sender' => [
                    'id' => $this->message->sender->id,
                    'name' => $this->message->sender->name,
                    'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($this->message->sender->name) . '&color=7F9CF5&background=EBF4FF',
                ],
                'receiver_id' => $this->message->receiver_id,
                'sender_id' => $this->message->sender_id,
                'media_url' => $this->message->media_url ? asset('storage/' . $this->message->media_url) : null,
                'media_type' => $this->message->media_type,
            ]
        ];
    }
}
