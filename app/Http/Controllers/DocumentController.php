<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use App\Services\Facades\DocumentFacade;

class DocumentController extends Controller
{
    public function sign(Document $document)
    {
        return DocumentFacade::sign($document);
    }

    public function downloadSignedDocument(Document $document, Request $request)
    {
        return DocumentFacade::downloadSignedDocument($document, $request);
    }
}
