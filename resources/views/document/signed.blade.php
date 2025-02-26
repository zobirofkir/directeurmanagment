<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Signé</title>
    <style>
        body {
            font-family: 'Amiri', sans-serif;
            direction: rtl;
            padding: 20px;
            color: #333;
        }

        .page {
            margin-bottom: 20px;
            border: 1px solid #ccc;
        }

        .signature-container {
            text-align: center;
            margin-top: 20px;
        }

        .signature-container img {
            max-width: 150px;
            border: 1px solid #000;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <!-- عرض صفحات الـ PDF كصور -->
    @foreach($pages as $page)
        <div class="page">
            <img src="{{ $page }}" alt="Page {{ $loop->iteration }}" style="width: 100%;">
        </div>
    @endforeach

    <!-- إضافة التوقيع -->
    <div class="signature-container">
        <h2>التوقيع:</h2>
        @if($signature)
            <img src="{{ $signature }}" alt="Signature">
        @else
            <p>لا يوجد توقيع متاح.</p>
        @endif
    </div>
</body>
</html>
