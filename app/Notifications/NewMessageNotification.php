<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Message;
use App\Models\User;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $sender;

    public function __construct(Message $message, User $sender)
    {
        $this->message = $message;
        $this->sender = $sender;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mailMessage = (new MailMessage)
            ->subject('New Message from ' . $this->sender->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have received a new message from ' . $this->sender->name);

        if ($this->message->content) {
            $mailMessage->line('Message: ' . $this->message->content);
        }

        if ($this->message->media_url) {
            $mailMessage->line('This message includes a ' . $this->message->media_type . ' attachment.');
        }

        return $mailMessage
            ->action('View Message', url('/admin/chats'))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'message_id' => $this->message->id,
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'content' => $this->message->content
        ];
    }
}
