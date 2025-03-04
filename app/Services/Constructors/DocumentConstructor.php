<?php

namespace App\Services\Constructors;

use App\Models\Document;

use Illuminate\Http\Request;

interface DocumentConstructor
{
    public function sign(Document $document);

    public function downloadSignedDocument(Document $document, Request $request);
}
