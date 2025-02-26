<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf as PdfToImage;

class DocumentController extends Controller
{
    public function sign(Document $document)
    {
        return view('document.sign', compact('document'));
    }

    public function downloadSignedDocument(Document $document, Request $request)
    {
        $signature = $request->input('signature');
        $filePath = Storage::disk('public')->path($document->file_path);
        $tempDir = storage_path('app/public/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }
        $pdfToImage = new PdfToImage($filePath);
        $pages = [];
        for ($i = 1; $i <= $pdfToImage->getNumberOfPages(); $i++) {
            $imagePath = storage_path('app/public/temp/page_' . $i . '.jpg');
            $pdfToImage->setPage($i)->saveImage($imagePath);
            $pages[] = Storage::disk('public')->url('temp/page_' . $i . '.jpg');
        }
        $pdf = DomPDF::loadView('document.signed', [
            'document' => $document,
            'signature' => $signature,
            'pages' => $pages,
        ]);
        $response = $pdf->download('signed_document.pdf');
        foreach ($pages as $page) {
            $imagePath = storage_path('app/public/temp/page_' . $i . '.jpg');
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        return $response;
    }
}
