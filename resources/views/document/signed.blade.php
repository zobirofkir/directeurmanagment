<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المستند الموقع</title>
    <style>
        @font-face {
            font-family: 'Amiri';
            font-style: normal;
            font-weight: 400;
            src: url('{{ public_path('fonts/Amiri-Regular.ttf') }}') format('truetype');
        }
        body {
            font-family: 'Amiri', sans-serif; /* استخدام الخط العربي */
            direction: rtl; /* اتجاه النص من اليمين لليسار */
        }
        .document-container {
            margin-bottom: 20px;
        }
        .signature-container {
            text-align: center;
            margin-top: 20px;
        }
        .signature-container img {
            border: 1px solid #000;
            max-width: 200px;
        }
    </style>
</head>
<body>
    <h1>المستند الموقع: {{ $document->title }}</h1>
    <div class="document-container">
        <iframe src="{{ Storage::url($document->file_path) }}" width="100%" height="500px"></iframe>
    </div>
    <div class="signature-container">
        <h2>التوقيع:</h2>
        <img src="{{ $signature }}" alt="التوقيع">
    </div>
</body>
</html>
