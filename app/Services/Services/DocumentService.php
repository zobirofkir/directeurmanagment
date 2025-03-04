<?php

namespace App\Services\Services;

use App\Services\Constructors\DocumentConstructor;
use App\Models\Document;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf as PdfToImage;


class DocumentService implements DocumentConstructor
{
    public function sign(Document $document)
    {
        return view('document.sign', compact('document'));
    }

    public function downloadSignedDocument(Document $document, Request $request)
    {
        $signature = $request->input('signature');
        $language = $request->input('language', 'en');

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
            $pages[] = $imagePath;
        }

        $viewName = 'document.signed_' . $language;
        $pdf = DomPDF::loadView($viewName, [
            'document' => $document,
            'signature' => $signature,
            'pages' => $pages,
        ]);

        switch ($language) {
            case 'ar':
                $pdf->setOption('defaultFont', 'Amiri');
                break;
            case 'fr':
                $pdf->setOption('defaultFont', 'DejaVu Sans');
                break;
            case 'en':
            default:
                $pdf->setOption('defaultFont', 'Helvetica');
                break;
        }

        $response = $pdf->download('signed_document_' . $language . '.pdf');

        foreach ($pages as $page) {
            if (file_exists($page)) {
                unlink($page);
            }
        }

        $archivePath = 'archived_documents/' . basename($document->file_path);
        Storage::disk('public')->move($document->file_path, $archivePath);

        $document->update([
            'file_path' => $archivePath,
            'archived' => true,
        ]);

        return $response;
    }
}
