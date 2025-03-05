<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDocumentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Document $document
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouveau document disponible')
            ->line('Un nouveau document a été ajouté : ' . $this->document->title)
            ->action('Voir le document', route('filament.admin.resources.documents.index'))
            ->line('Merci de votre attention!');
    }

    public function toArray($notifiable): array
    {
        return [
            'document_id' => $this->document->id,
            'title' => $this->document->title,
            'message' => 'Un nouveau document a été ajouté',
        ];
    }
}
