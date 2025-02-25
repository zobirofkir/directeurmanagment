<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function sign(Document $document)
    {
        return view('document.sign', compact('document'));
    }

    public function downloadSignedDocument(Document $document, Request $request)
    {
        $signature = $request->input('signature');

        $documentPath = Storage::path($document->file_path);

        $pdf = Pdf::loadView('document.signed', [
            'document' => $document,
            'signature' => $signature,
            'documentPath' => $documentPath,
        ]);

        $pdf->setOption('defaultFont', 'Amiri'); 
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf->download('signed_document.pdf');
    }
}
