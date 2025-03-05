<?php

namespace App\Mail;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Document $document
    ) {}

    public function build()
    {
        return $this->subject('Nouveau document: ' . $this->document->title)
            ->markdown('emails.documents.created', [
                'document' => $this->document,
                'url' => route('filament.admin.resources.documents.edit', $this->document),
                'userRole' => $this->document->user->role,
                'userName' => $this->document->user->name,
            ]);
    }
}
