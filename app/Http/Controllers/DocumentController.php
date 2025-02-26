<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Tcpdf\Fpdi;

class DocumentController extends Controller
{
    public function sign(Document $document)
    {
        return view('document.sign', compact('document'));
    }

    public function downloadSignedDocument(Document $document, Request $request)
    {
        $request->validate([
            'signature' => 'required|string'
        ]);

        $signatureData = str_replace('data:image/png;base64,', '', $request->input('signature'));
        $signatureData = base64_decode($signatureData);

        if (!$signatureData) {
            return redirect()->back()->with('error', 'التوقيع غير صالح.');
        }

        $documentsDir = storage_path('app/documents');
        if (!file_exists($documentsDir)) {
            mkdir($documentsDir, 0755, true);
        }

        $signaturePath = $documentsDir . '/' . uniqid('signature_', true) . '.png';
        if (!file_put_contents($signaturePath, $signatureData)) {
            return redirect()->back()->with('error', 'فشل حفظ التوقيع.');
        }

        $documentPath = Storage::disk('local')->path($document->file_path);

        if (!Storage::disk('local')->exists($document->file_path)) {
            return redirect()->back()->with('error', 'ملف PDF غير موجود.');
        }

        try {
            $pdf = new Fpdi();

            $pageCount = $pdf->setSourceFile($documentPath);
            if ($pageCount === 0) {
                return redirect()->back()->with('error', 'ملف PDF غير صالح.');
            }

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);

                if ($pageNo == $pageCount) {
                    $pdf->Image($signaturePath, 10, $size['height'] - 50, 100, 0, 'PNG');
                }
            }

            $signedFilePath = 'documents/' . uniqid('signed_document_', true) . '.pdf';
            $pdfContent = $pdf->Output('', 'S');

            if (!Storage::disk('local')->put($signedFilePath, $pdfContent)) {
                throw new \Exception('فشل حفظ المستند الموقع.');
            }

            unlink($signaturePath);

            return response()->download(Storage::disk('local')->path($signedFilePath), 'signed_document.pdf', ['Content-Type' => 'application/pdf'])
                             ->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'فشل تحميل المستند الموقع: ' . $e->getMessage());
        }
    }
}
