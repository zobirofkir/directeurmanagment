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

    public function selectSignaturePosition(Document $document, Request $request)
    {
        $signature = $request->input('signature');
        $language = $request->input('language', 'fr');
        return DocumentFacade::selectSignaturePosition($document, $signature, $language);
    }

    public function downloadSignedDocument(Document $document, Request $request)
    {
        return DocumentFacade::downloadSignedDocument($document, $request);
    }
}
