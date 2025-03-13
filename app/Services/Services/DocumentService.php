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

    public function selectSignaturePosition(Document $document, $signature, $language)
    {
        return view('document.position', compact('document', 'signature', 'language'));
    }

    public function downloadSignedDocument(Document $document, Request $request)
    {
        $signature = $request->input('signature');
        $language = $request->input('language', 'en');
        $positionX = floatval($request->input('position_x', 0));
        $positionY = floatval($request->input('position_y', 0));
        $scale = floatval($request->input('scale', 1));

        // Create temp directory if it doesn't exist
        $tempDir = storage_path('app/public/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        // Convert signature data URL to image file
        $signatureImage = null;
        if (preg_match('/^data:image\/(\w+);base64,/', $signature, $type)) {
            $data = substr($signature, strpos($signature, ',') + 1);
            $type = strtolower($type[1]);

            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('Invalid image type');
            }

            $data = base64_decode($data);
            if ($data === false) {
                throw new \Exception('Failed to decode base64');
            }

            $signatureName = 'signature_' . uniqid() . '.' . $type;
            $signatureImage = $tempDir . '/' . $signatureName;
            file_put_contents($signatureImage, $data);
        } else {
            throw new \Exception('Invalid signature format');
        }

        // Convert PDF to images with higher resolution
        $filePath = Storage::disk('public')->path($document->file_path);
        $pdfToImage = new PdfToImage($filePath);
        $pages = [];

        for ($i = 1; $i <= $pdfToImage->getNumberOfPages(); $i++) {
            $imagePath = $tempDir . '/page_' . $i . '.jpg';
            $pdfToImage->setPage($i)
                       ->setResolution(300) // Higher resolution
                       ->saveImage($imagePath);
            $pages[] = $imagePath;
        }

        // Create PDF with proper positioning
        $viewData = [
            'document' => $document,
            'signature' => 'file://' . $signatureImage,
            'pages' => array_map(function($page) {
                return 'file://' . $page;
            }, $pages),
            'position_x' => $positionX,
            'position_y' => $positionY,
            'scale' => $scale
        ];

        $pdf = DomPDF::loadView('document.signed_' . $language, $viewData);

        // Configure PDF options for precise positioning
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption([
            'enable_php' => true,
            'isPhpEnabled' => true,
            'isRemoteEnabled' => true,
            'enable_remote' => true,
            'dpi' => 300,
            'defaultPaperSize' => 'A4',
            'defaultFont' => 'DejaVu Sans',
            'chroot' => storage_path('app/public'),
            'tempDir' => storage_path('app/public/temp'),
            'logOutputFile' => storage_path('logs/pdf.htm'),
            'fontDir' => storage_path('fonts/'),
            'isHtml5ParserEnabled' => true,
            'isFontSubsettingEnabled' => true,
            'defaultMediaType' => 'print',
            'defaultPaperOrientation' => 'portrait',
            'debugPng' => false,
            'debugKeepTemp' => false,
            'debugCss' => false,
            'debugLayout' => false,
            'debugLayoutLines' => false,
            'debugLayoutBlocks' => false,
            'debugLayoutInline' => false,
            'debugLayoutPaddingBox' => false,
        ]);

        // Set language-specific font
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

        // Generate PDF
        $response = $pdf->download('signed_document_' . $language . '.pdf');

        // Clean up temporary files
        foreach ($pages as $page) {
            if (file_exists($page)) {
                unlink($page);
            }
        }
        if (file_exists($signatureImage)) {
            unlink($signatureImage);
        }

        // Archive the document
        $archivePath = 'archived_documents/' . basename($document->file_path);
        Storage::disk('public')->move($document->file_path, $archivePath);

        $document->update([
            'file_path' => $archivePath,
            'archived' => true,
        ]);

        return $response;
    }
}
