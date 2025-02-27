<?php

namespace App\Mail;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentCreated extends Mailable
{
    use Queueable, SerializesModels;

    public Document $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function build()
    {
        return $this->subject('Nouveau document à signer') 
                    ->view('emails.document_created', ['document' => $this->document]);
    }
}
